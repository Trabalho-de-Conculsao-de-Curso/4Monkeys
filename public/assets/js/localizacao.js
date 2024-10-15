navigator.geolocation.getCurrentPosition(registrarPosicao, tratarErro);

function registrarPosicao(posicao) {
    alert("Latitude: " + posicao.coords.latitude +" Longitude: " + posicao.coords.longitude);
}

function tratarErro(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            alert("Usuário negou o acesso a Geolocalização");
            break;
        case error.POSITION_UNAVAILABLE:
            alert("A informação a localização está indisponível");
            break;
        case error.TIMEOUT:
            alert("A requisição para o usuário permitir acesso a localização expirou.");
            break;
        // case error.UNKNOWN_ERROR:
        //     alert("An unknown error occurred.");
        //     break;
    }
}
