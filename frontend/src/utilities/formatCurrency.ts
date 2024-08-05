const CURRENCY_FORMAT = new Intl.NumberFormat(undefined, {
    currency: "USD",
    style: "currency",
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

export default function formatCurrency(number: number) {
    return CURRENCY_FORMAT.format(number / 100).replace('US', '');
}