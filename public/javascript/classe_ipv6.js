function verifyIPv6(ipv6) {
    const ipv6Regex = /^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/;
    return ipv6Regex.test(ipv6);
}

function extendIPv6(ipv6) {
    const segments = ipv6.split('::');
    const left = segments[0].split(':').filter(Boolean);
    const right = segments[1] ? segments[1].split(':').filter(Boolean) : [];
    const missing = 8 - (left.length + right.length);
    const fullAddress = [...left, ...Array(missing).fill('0000'), ...right];
    return fullAddress.map(segment => segment.padStart(4, '0')).join(':');
}

function simplifyIPv6(ipv6) {
    const segments = ipv6.split(':').map(segment => segment.replace(/^0+/, '') || '0');
    const maxZeroSequence = segments.join(':').match(/(^|:)0(:0)+(:|$)/);
    return maxZeroSequence ? segments.join(':').replace(maxZeroSequence[0], '::') : segments.join(':');
}



document.getElementById('simplifier-button').addEventListener('click', function (event) {
    event.preventDefault();
    var ipv6 = document.getElementById('ipv6').value;
    const resultDiv = document.getElementById('resultDiv');
    
    if (verifyIPv6(ipv6)) {
        const simplified = simplifyIPv6(ipv6);
        resultDiv.innerHTML = `<p> Adresse IPv6 simplifié : ${simplified}</p>`;
    } else {
        resultDiv.innerHTML = `<p> pas bon </p>`;
    }
});

document.getElementById('etendre-button').addEventListener('click', function (event) {
    event.preventDefault();
    var ipv6 = document.getElementById('ipv6').value;
    const resultDiv = document.getElementById('resultDiv');
    
    if (verifyIPv6(ipv6)) {
        const extended = extendIPv6(ipv6);
        resultDiv.innerHTML = `<p> Adresse IPv6 étendue : ${extended}</p>`;
    } else {
        resultDiv.innerHTML = `<p> pas bon </p>`;
    }
});