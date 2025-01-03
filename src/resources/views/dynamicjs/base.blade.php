function base(){[native/code]}

base.loading = {show:ko.observable(false)};

base.Auth = {!! !empty(Auth::user()) ? json_encode(Auth::user()) : json_encode(new stdClass) !!};

base.post = function(url, payload, callback, type, loading) {
    let headers = {
        'Authorization': 'Bearer ' + tokenApi,
        'X-CSRF-TOKEN': "{{csrf_token()}}",
    };
    if (!loading) {
        base.loading.show(true);
    }

    $.ajax({
        url: url,
        data: payload,
        type: type || 'POST',
        xhrFields: {
            withCredentials: true
        },
        headers: headers,
        success: function(response) {
            if(typeof(callback) == 'function') callback(response);
        },
        error: function(err) {
            console.log(err);
            Alert.error('Ocorreu um erro, contate o administrador do sistema!!!', 'Ops...');
        },
        complete: function() {
            if(!loading) base.loading.show(false);
        }
    });
}

base.postImage = function(url, formData, callback, type, loading) {
    let headers = {
        'Authorization': 'Bearer ' + tokenApi,
        'X-CSRF-TOKEN': "{{csrf_token()}}",
    };
    if (!loading) {
        base.loading.show(true);
    }

    $.ajax({
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        type: type || 'POST',
        xhrFields: {
            withCredentials: true
        },
        headers:headers,
        success: function(response) {
            if(typeof(callback) == 'function') callback(response);
        },
        error: function(err) {
            console.log(err);
            Alert.error('Ocorreu um erro, contate o administrador do sistema!!!', 'Ops...');
        },
        complete: function() {
            if(!loading) base.loading.show(false);
        }
    });
};

base.mascaraCnpj = function(valor) {
    if (!valor) return ' - ';
    return valor.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
}

base.mascaraTelefone = function(value) {

    if (!value) return ' - ';

    value=value.replace(/\D/g,"");
    value=value.replace(/^(\d{2})(\d)/g,"($1) $2");
    value=value.replace(/(\d)(\d{4})$/,"$1-$2");
    return value;
}

base.mascaraCpf = function (valor) {
    if (!valor) return ' - ';
    return valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
}

base.mascaraCep = function (str){
	var re = /^([\d]{5})-*([\d]{3})/;
	if(re.test(str)){
		return str.replace(re,"$1-$2");
	}
	return str;
}

base.numeroParaMoeda = function(n, c, d, t) {
    var value = 0;
    c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    value = s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    return 'R$ '+value;
}

base.monthStringEn = function(date) {
    if (!date) return;
    const dateTemp = new Date(date);
    const year = new Intl.DateTimeFormat('pt-br', { year: 'numeric' }).format(dateTemp);
    const month = new Intl.DateTimeFormat('pt-br', { month: 'short' }).format(dateTemp).replace('.', '');
    const day = new Intl.DateTimeFormat('pt-br', { day: '2-digit' }).format(dateTemp);
    return `${day}/${month}/${year}`;
}

base.dateTimeStringEn = function(date) {
    if (!date) return;
    const dateTemp = new Date(date);
    const year = new Intl.DateTimeFormat('pt-br', { year: 'numeric' }).format(dateTemp);
    const month = new Intl.DateTimeFormat('pt-br', { month: 'short' }).format(dateTemp).replace('.', '');
    const day = new Intl.DateTimeFormat('pt-br', { day: '2-digit' }).format(dateTemp);
    const hour = new Intl.DateTimeFormat('pt-br', { hour: '2-digit' }).format(dateTemp);
    const minute = new Intl.DateTimeFormat('pt-br', { minute: '2-digit' }).format(dateTemp);
    return `${day}/${month}/${year} ${hour}h${minute}`;
}

base.monthString = function(monthDate) {
    let dateTemp = monthDate.split('/');
    switch (parseInt(dateTemp[0])) {
        case  1: return 'JAN ' + dateTemp[1];
        case  2: return 'FEV ' + dateTemp[1];
        case  3: return 'MAR ' + dateTemp[1];
        case  4: return 'ABR ' + dateTemp[1];
        case  5: return 'MAI ' + dateTemp[1];
        case  6: return 'JUN ' + dateTemp[1];
        case  7: return 'JUL ' + dateTemp[1];
        case  8: return 'AGO ' + dateTemp[1];
        case  9: return 'SET ' + dateTemp[1];
        case 10: return 'OUT ' + dateTemp[1];
        case 11: return 'NOV ' + dateTemp[1];
        case 12: return 'DEZ ' + dateTemp[1];
        default: '';
    }
}

base.monthDescription = function(monthNumber) {
    switch (monthNumber) {
        case  1: return 'Jan';
        case  2: return 'Fev';
        case  3: return 'Mar';
        case  4: return 'Abr';
        case  5: return 'Mai';
        case  6: return 'Jun';
        case  7: return 'Jul';
        case  8: return 'Ago';
        case  9: return 'Set';
        case 10: return 'Out';
        case 11: return 'Nov';
        case 12: return 'Dez';
        default: '';
    }
}

base.displayImage = function(filename) {
    if (!filename) {
        return;
    }

    let urlStorage = "{{ route('storage.upload.show') }}";
    return filename.indexOf('http') !== -1
        ? filename
        : urlStorage + '/' + filename;
}

base.createFormData = function(formData, key, data) {
    if (data === Object(data) || Array.isArray(data)) {
        for (var i in data) {
            base.createFormData(formData, key + '[' + i + ']', data[i]);
        }
    } else {
        formData.append(key, data);
    }
}

base.getParamUrl = function(param) {
    let searchParams = new URLSearchParams(window.location.search);
    return searchParams.get(param) == null ? undefined : searchParams.get(param);
}

base.imagesAllCropped = function(files) {
    if (files.length > 0) {
        let hasImageError = ko.utils.arrayFirst(files, function(item) {
            return item.fileCropped === undefined;
        });
        return hasImageError;
    }
}

base.addDay = function(date, days) {
    const newDate = new Date(date)
    return new Date(newDate.setDate(newDate.getDate() + days));
}

base.getParameterByName = function(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

base.removeNonNumeric = function(value) {
    return value.replace(/\D/g,"");
}
