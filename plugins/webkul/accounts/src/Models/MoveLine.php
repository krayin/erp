<?php

namespace Webkul\Account\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Partner\Models\Partner;
use Webkul\Sale\Models\Product;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;
use Webkul\Support\Models\UOM;

class MoveLine extends Model
{
    use HasFactory;

    protected $table = 'accounts_account_move_lines';

    protected $fillable = [
        'sort',
        'move_id',
        'journal_id',
        'company_id',
        'company_currency_id',
        'reconcile_id',
        'payment_id',
        'tax_repartition_line_id',
        'account_id',
        'currency_id',
        'partner_id',
        'group_tax_id',
        'tax_line_id',
        'tax_group_id',
        'statement_id',
        'statement_line_id',
        'product_id',
        'product_uom_id',
        'created_by',
        'move_name',
        'parent_state',
        'reference',
        'name',
        'matching_number',
        'display_type',
        'date',
        'invoice_date',
        'date_maturity',
        'discount_date',
        'analytic_distribution',
        'debit',
        'credit',
        'balance',
        'amount_currency',
        'tax_base_amount',
        'amount_residual',
        'amount_residual_currency',
        'quantity',
        'price_unit',
        'price_subtotal',
        'price_total',
        'discount',
        'discount_amount_currency',
        'discount_balance',
        'is_imported',
        'tax_tag_invert',
        'reconciled',
        'is_downpayment',
        'full_reconcile_id',
    ];

    public function move()
    {
        return $this->belongsTo(Move::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function groupTax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function taxLine()
    {
        return $this->belongsTo(Tax::class);
    }

    public function taxGroup()
    {
        return $this->belongsTo(TaxGroup::class);
    }

    public function statement()
    {
        return $this->belongsTo(BankStatement::class);
    }

    public function statementLine()
    {
        return $this->belongsTo(BankStatementLine::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productUom()
    {
        return $this->belongsTo(UOM::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    public function moveLines()
    {
        return $this->hasMany(MoveLine::class, 'reconcile_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function fullReconcile()
    {
        return $this->belongsTo(FullReconcile::class);
    }

    public static function createOrUpdateProductLine(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            $lines = new Collection();

            if (!empty($data['id'])) {
                $productLine = self::find($data['id']);
                $productLine->fill(array_merge($data, [
                    'display_type' => 'product',
                    'name' => $data['name'] ?? Product::find($data['product_id'])?->name,
                ]));
            } else {
                $productLine = new self();
                $productLine->fill(array_merge($data, [
                    'display_type' => 'product',
                    'name' => $data['name'] ?? Product::find($data['product_id'])?->name,
                ]));
            }

            $productLine->calculateAmounts($data);
            $productLine->save();
            $lines->push($productLine);

            if (!empty($data['tax'])) {
                $existingTaxLines = self::where('move_id', $data['move_id'])
                    ->where('display_type', 'tax')
                    ->get();

                $taxes = Tax::whereIn('id', $data['tax'])->get();

                $existingTaxLines->each(function ($taxLine) use ($data) {
                    if (!in_array($taxLine->tax_line_id, $data['tax'])) {
                        $taxLine->delete();
                    }
                });

                foreach ($taxes as $tax) {
                    $taxLine = $existingTaxLines->first(function ($line) use ($tax) {
                        return $line->tax_line_id === $tax->id;
                    });

                    if (!$taxLine) {
                        $taxLine = new self();
                    }

                    $taxLineData = array_merge($data, [
                        'display_type' => 'tax',
                        'name' => $tax->name,
                        'product_id' => null,
                        'product_uom_id' => null,
                        'quantity' => null,
                        'price_unit' => null,
                        'tax_line_id' => $tax->id,
                    ]);

                    $taxLine->fill($taxLineData);
                    $taxLine->calculateAmounts($taxLineData);
                    $taxLine->save();
                    $lines->push($taxLine);
                }
            }

            $paymentLine = self::where('move_id', $data['move_id'])
                ->where('display_type', 'payment_term')
                ->first();

            if (!$paymentLine) {
                $paymentLine = new self();
            }

            $paymentLineData = array_merge($data, [
                'display_type' => 'payment_term',
                'name' => null,
                'product_id' => null,
                'product_uom_id' => null,
                'quantity' => null,
                'price_unit' => null,
            ]);

            $paymentLine->fill($paymentLineData);
            $paymentLine->calculateAmounts($paymentLineData);
            $paymentLine->save();
            $lines->push($paymentLine);

            return $lines;
        });
    }

    /**
     * Calculate amounts based on display type
     */
    public function calculateAmounts(array $data): void
    {
        if ($this->display_type === 'product') {
            $this->calculateProductAmounts($data);
        } elseif ($this->display_type === 'tax') {
            $this->calculateTaxAmounts($data);
        } elseif ($this->display_type === 'payment_term') {
            $this->calculatePaymentTermAmounts($data);
        }
    }

    /**
     * Calculate product line amounts
     */
    protected function calculateProductAmounts(array $data): void
    {
        $quantity = floatval($data['quantity'] ?? 0);
        $priceUnit = floatval($data['price_unit'] ?? 0);
        $discount = floatval($data['discount'] ?? 0);

        $baseSubtotal = $quantity * $priceUnit;
        $discountAmount = $baseSubtotal * ($discount / 100);
        $subtotalAfterDiscount = $baseSubtotal - $discountAmount;

        $taxAmount = 0;
        $includedTaxAmount = 0;

        if (!empty($data['tax'])) {
            $taxes = Tax::whereIn('id', $data['tax'])->get();

            foreach ($taxes as $tax) {
                if ($tax->include_base_amount) {
                    $includedTaxRate = floatval($tax->amount) / 100;
                    $includedTaxAmount += $subtotalAfterDiscount - ($subtotalAfterDiscount / (1 + $includedTaxRate));
                }
            }

            $baseForAdditionalTax = $subtotalAfterDiscount - $includedTaxAmount;
            foreach ($taxes as $tax) {
                if (!$tax->include_base_amount) {
                    $taxAmount += $baseForAdditionalTax * (floatval($tax->amount) / 100);
                }
            }
        }

        $this->price_subtotal = $subtotalAfterDiscount - $includedTaxAmount;
        $this->price_total = $subtotalAfterDiscount + $taxAmount;
        $this->discount_amount_currency = $discountAmount;
        $this->discount_balance = $discountAmount;
        $this->debit = 0;
        $this->credit = $this->price_total;
        $this->balance = -$this->price_total;
        $this->amount_currency = $this->balance;
        $this->amount_residual = $this->balance;
        $this->amount_residual_currency = $this->balance;
    }

    /**
     * Calculate tax line amounts
     */
    protected function calculateTaxAmounts(array $data): void
    {
        if (empty($this->tax_line_id)) {
            return;
        }

        $tax = $this->taxLine;
        $productLine = self::where('move_id', $data['move_id'])
            ->where('display_type', 'product')
            ->first();

        if (!$productLine) {
            return;
        }

        $baseAmount = $productLine->price_subtotal;
        $taxAmount = $baseAmount * (floatval($tax->amount) / 100);

        $this->tax_base_amount = $baseAmount;
        $this->debit = 0;
        $this->credit = $taxAmount;
        $this->balance = -$taxAmount;
        $this->amount_currency = $this->balance;
        $this->amount_residual = $this->balance;
        $this->amount_residual_currency = $this->balance;
    }

    /**
     * Calculate payment term line amounts
     */
    protected function calculatePaymentTermAmounts(array $data): void
    {
        $totalAmount = self::where('move_id', $data['move_id'])
            ->whereIn('display_type', ['product', 'tax'])
            ->sum('credit');

        $this->debit = $totalAmount;
        $this->credit = 0;
        $this->balance = $totalAmount;
        $this->amount_currency = $this->balance;
        $this->amount_residual = $this->balance;
        $this->amount_residual_currency = $this->balance;
    }

    /**
     * Override the save method to ensure calculations are performed
     */
    public function save(array $options = [])
    {
        return parent::save($options);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($moveLine) {
            $moveLine->sort = self::max('sort') + 1;
        });
    }
}
