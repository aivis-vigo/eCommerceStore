export default function toKebabCase(text: string): string {
    return text.replace(/\s+/g, '-');
}