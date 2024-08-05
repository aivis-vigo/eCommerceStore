export default function toKebabCase(text: string): string {
    return text.toLowerCase().replace(/\s+/g, '-');
}