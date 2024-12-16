function isValidIPv4(ipv4) {
    const segments = ipv4.split('.');
    if (segments.length !== 4) return false;

    return segments.every(segment => {
        const num = Number(segment);
        return segment !== '' && !isNaN(num) && num >= 0 && num <= 255;
    });
}

