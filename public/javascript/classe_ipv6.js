window.onload = init;

function verifyIPv6(ipv6) {
    const ipv6Regex = /^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/;
    return ipv6Regex.test(ipv6);
}

function containsIPv4(ipv6) {
    const ipv4InIPv6Regex = /::(?:[fF]{4}:)?((25[0-5]|(2[0-4][0-9])|(1[0-9][0-9])|([1-9]?[0-9]))\.){3}(25[0-5]|(2[0-4][0-9])|(1[0-9][0-9])|([1-9]?[0-9]))$/;
    return ipv4InIPv6Regex.test(ipv6);
}

function classifyIPv6(ipv6) {
    if (!verifyIPv6(ipv6)) {
        let textContent = "Adresse IPv6 invalide";
        let className = 'error';
        let title = "Erreur"
        showError(textContent, className, title)
    }

    let classifications = "";

    if (containsIPv4(ipv6)) {
        classifications += "Adresse IPv6 contenant une adresse IPv4 intégrée. ";
        const ipv4 = ipv6.split(":").pop();
        classifications += `Adresse IPv4 : ${ipv4}. `;
    }

    const extendedIPv6 = extendIPv6(ipv6).extendedIPv6.toUpperCase();
    const simplifiedIPv6 = simplifyIPv6(ipv6).simplifiedIPv6.toUpperCase();

    if (extendedIPv6.startsWith("FE80:") || simplifiedIPv6.startsWith("FE80:")) {
        classifications += "Adresse IPv6 Link-Local. ";
    }

    const firstHexGroup = parseInt(extendedIPv6.split(":")[0], 16);
    if (firstHexGroup >= 0x2000 && firstHexGroup <= 0x3FFF) {
        classifications += "Adresse IPv6 Globale. ";
    }

    if (extendedIPv6.startsWith("FD") || simplifiedIPv6.startsWith("FD")) {
        classifications += "Adresse IPv6 Locale Unique (LUA). ";
    }

    if (!classifications) {
        let textContent = "Adresse IPv6 non classifiable (ou autre)";
        let className = 'info';
        let title = "Info"
        showError(textContent, className, title)

    }

    return classifications.trim();
}


function extendIPv6(ipv6) {
    let classification = "";

    let ipv4Part = "";
    if (containsIPv4(ipv6)) {
        classification += "Adresse IPv6 contenant une adresse IPv4 intégrée. ";
        ipv4Part = ipv6.split(":").pop();
        classification += `Adresse IPv4 : ${ipv4Part}. `;
    }

    const segments = ipv6.split('::');
    const left = segments[0].split(':').filter(Boolean);
    const right = segments[1] ? segments[1].split(':').filter(Boolean) : [];

    let missing = 8 - (left.length + right.length);
    if (ipv4Part) {
        missing +=1;
    }

    const fullAddress = [...left, ...Array(missing).fill('0000'), ...right];

    let extendedIPv6 = fullAddress.map(segment => segment.padStart(4, '0')).join(':');

    if (ipv4Part && !ipv6.endsWith(ipv4Part)) {
        extendedIPv6 += `:${ipv4Part}`;
    }

    return { extendedIPv6, classification };
}



function simplifyIPv6(ipv6) {
    let classification = "";

    if (containsIPv4(ipv6)) {
        classification += "Adresse IPv6 contenant une adresse IPv4 intégrée. ";
        const ipv4 = ipv6.split(":").pop();  // Prend la partie IPv4
        classification += `Adresse IPv4 : ${ipv4}. `;
    }

    const segments = ipv6.split(':').map(segment => segment.replace(/^0+/, '') || '0');

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
    simplifiedIPv6 = simplifiedIPv6.replace(/(:{2,})/, '::');

    // Gestion des cas où l'adresse commence ou finit par "::"
    if (simplifiedIPv6.startsWith(':')) {
        simplifiedIPv6 = '::' + simplifiedIPv6.substring(1);
    }
    if (simplifiedIPv6.endsWith(':')) {
        simplifiedIPv6 = simplifiedIPv6.substring(0, simplifiedIPv6.length - 1) + '::';
    }

    if (simplifiedIPv6 === '') {
        simplifiedIPv6 = '::';
    }

    // Si l'adresse initiale contient "::", on traite les deux parties avant et après "::"
    if (ipv6.includes("::")) {
        let [beforeDoubleColon, afterDoubleColon] = ipv6.split("::");
        if (beforeDoubleColon) {
            beforeDoubleColon = beforeDoubleColon.split(':').map(segment => segment.replace(/^0+/, '') || '0').join(':');
        }
        if (afterDoubleColon) {
            afterDoubleColon = afterDoubleColon.split(':').map(segment => segment.replace(/^0+/, '') || '0').join(':');
        }
        return { simplifiedIPv6: beforeDoubleColon + "::" + afterDoubleColon, classification };
    }

    return { simplifiedIPv6, classification };
}



function init(){
    document.getElementById('simplifier-button').addEventListener('click', function (event) {
        event.preventDefault();
        var ipv6 = document.getElementById('ipv6').value;
        const resultDiv = document.getElementById('resultDiv');

        if (verifyIPv6(ipv6)) {
            const { simplifiedIPv6, classification } = simplifyIPv6(ipv6);
            resultDiv.innerHTML = `<p>Adresse IPv6 simplifiée : ${simplifiedIPv6}</p><p>${classification}</p>`;
        } else {
            let textContent = "Adresse IPv6 invalide";
            let className = 'error';
            let title = "Erreur"
            showError(textContent, className, title)
        }
    });


    document.getElementById('etendre-button').addEventListener('click', function (event) {
        event.preventDefault();
        var ipv6 = document.getElementById('ipv6').value;
        const resultDiv = document.getElementById('resultDiv');

        if (verifyIPv6(ipv6)) {
            const { extendedIPv6, classification } = extendIPv6(ipv6);
            resultDiv.innerHTML = `<p>Adresse IPv6 étendue : ${extendedIPv6}</p><p>${classification}</p>`;
        } else {
            let textContent = "Adresse IPv6 invalide";
            let className = 'error';
            let title = "Erreur"
            showError(textContent, className, title)
        }
    });

    document.getElementById('classify-button').addEventListener('click', function (event) {
        event.preventDefault();
        var ipv6 = document.getElementById('ipv6').value.trim();
        const resultDiv = document.getElementById('resultDiv');

        const classifications = classifyIPv6(ipv6);
        resultDiv.innerHTML = `<p>${classifications}</p>`;
    });
}

window.onload = init;

function showError(textContent, className, title){
    Swal.fire({
        icon: className,
        title: title,
        text: textContent
    });
}

