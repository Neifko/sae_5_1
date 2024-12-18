window.onload = init;

function verifyIPv6(ipv6) {
    const ipv6Regex = /^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/;
    return ipv6Regex.test(ipv6);
}

function classifyIPv6(ipv6) {
    if (!verifyIPv6(ipv6)) {
        return "Adresse IPv6 non valide.";
    }

    const extendedIPv6 = extendIPv6(ipv6).toUpperCase(); 
    const simplifiedIPv6 = simplifyIPv6(ipv6).toUpperCase(); 

   
    if (extendedIPv6.startsWith("FE80:") || simplifiedIPv6.startsWith("FE80:")) {
        return "Adresse Link-Local.";
    }
    const firstHexGroup = parseInt(extendedIPv6.split(":")[0], 16); 
    if (firstHexGroup >= 0x2000 && firstHexGroup <= 0x3FFF) {
        return "Adresse Globale.";
    }

    if (extendedIPv6.startsWith("FD") || simplifiedIPv6.startsWith("FD")) {
        return "Adresse Locale Unique (LUA).";
    }

    return "Adresse IPv6 non classifiable (ou autre).";
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

    // gestion si l'adresse deja simplifié 
    const originalSegments = ipv6.split(':');
    let isSimplified = true;

    for (let i = 0; i < originalSegments.length; i++) {
        if (originalSegments[i] !== segments[i] && originalSegments[i] !== '' && segments[i] !== '0') {
            isSimplified = false;
            break;
        }
    }

    let bestStart = -1, bestLength = 0, currentStart = -1, currentLength = 0;

    for (let i = 0; i < segments.length; i++) {
        if (segments[i] === '0') {
            if (currentStart === -1) currentStart = i;
            currentLength++;
        } else {
            if (currentLength > bestLength) {
                bestStart = currentStart;
                bestLength = currentLength;
            }
            currentStart = -1;
            currentLength = 0;
        }
    }

    if (currentLength > bestLength) {
        bestStart = currentStart;
        bestLength = currentLength;
    }

    if (bestLength > 1) {
        segments.splice(bestStart, bestLength, '');
    }

    let simplifiedIPv6 = segments.join(':');

    // gestion remplacer la séquence de "00" par "::"
    simplifiedIPv6 = simplifiedIPv6.replace(/(:{2,})/, '::');  // Remplacer la séquence de "00" par "::"

    // gestion de commence ou se termine par ::
    if (simplifiedIPv6.startsWith(':')) {
        simplifiedIPv6 = '::' + simplifiedIPv6.substring(1);
    }
    if (simplifiedIPv6.endsWith(':')) {
        simplifiedIPv6 = simplifiedIPv6.substring(0, simplifiedIPv6.length - 1) + '::';
    }

    // gestion de que des 0 
    if (simplifiedIPv6 === '') {
        simplifiedIPv6 = '::';
    }

    // gestion de ::
    if (ipv6.includes("::")) {
        let [beforeDoubleColon, afterDoubleColon] = ipv6.split("::");
        if (beforeDoubleColon) {
            beforeDoubleColon = beforeDoubleColon.split(':').map(segment => segment.replace(/^0+/, '') || '0').join(':');
        }
        if (afterDoubleColon) {
            afterDoubleColon = afterDoubleColon.split(':').map(segment => segment.replace(/^0+/, '') || '0').join(':');
        }
        return beforeDoubleColon + "::" + afterDoubleColon;
    }

    return simplifiedIPv6;
}

function init(){
document.getElementById('simplifier-button').addEventListener('click', function (event) {
    event.preventDefault();
    var ipv6 = document.getElementById('ipv6').value;
    const resultDiv = document.getElementById('resultDiv');

    if (verifyIPv6(ipv6)) {
        const simplified = simplifyIPv6(ipv6);
       
        resultDiv.innerHTML = `<p> Adresse IPv6 simplifié : ${simplified}</p>`;
    } else {
        resultDiv.innerHTML = `<p> L'adresse IPv6 n'est pas valide. </p>`;
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
        resultDiv.innerHTML = `<p> L'adresse IPv6 n'est pas valide. </p>`;
    }
});
}

document.getElementById('classify-button').addEventListener('click', function (event) {
    event.preventDefault();
    var ipv6 = document.getElementById('ipv6').value;
    const resultDiv = document.getElementById('resultDiv');

    const classification = classifyIPv6(ipv6);
    resultDiv.innerHTML = `<p>${classification}</p>`;
});