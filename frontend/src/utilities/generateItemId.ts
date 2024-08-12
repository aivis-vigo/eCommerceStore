interface Attribute {
    attribute_name: string;
    attribute_options: {
        attribute_option_value: string;
    }[];
}

export default function generateItemId(name: string, attributes: Attribute[] | Record<string, string>) {
    // Convert the item name to a lowercase hyphenated format
    const formattedName = name.toLowerCase().replace(/\s+/g, '-');

    if (Array.isArray(attributes)) {
        // Process attributes array
        const result = attributes.reduce((acc, attribute) => {
            const key = attribute.attribute_name.toLowerCase();
            const value = attribute.attribute_options[0].attribute_option_value.toLowerCase();
            acc[key] = value;
            return acc;
        }, {} as Record<string, string>);

        const properties = Object.entries(result);

        if (properties.length > 0) {
            const formattedString = properties
                .map(([key, value]) => {
                    // Convert the key to the desired format and concatenate it with the value
                    const formattedKey = key.replace(/([A-Z])/g, '-$1').toLowerCase();
                    return `${formattedKey}-${value}`;
                })
                .join('-');

            return `${formattedName}-${formattedString}`;
        } else {
            return formattedName;
        }
    }

    if (typeof attributes === 'object' && attributes !== null) {
        const result = Object.entries(attributes).reduce((acc, [key, value]) => {
            acc[key.toLowerCase()] = value.toLowerCase();
            return acc;
        }, {} as Record<string, string>);

        const properties = Object.entries(result);
        const formattedString = properties
            .map(([key, value]) => {
                const formattedKey = key.replace(/([A-Z])/g, '-$1').toLowerCase();
                return `${formattedKey}-${value}`;
            })
            .join('-');

        return properties.length > 0 ? `${formattedName}-${formattedString}` : formattedName;
    }

    // Return a fallback if attributes is neither an array nor an object
    return formattedName;
}