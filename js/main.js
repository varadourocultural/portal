var varadouroApp = varadouroApp || {},
    yearRange = 2015,
    markers = [];

var infowindow = null;

(function(window, document, $) {
    'use strict';

    var namespace = document.getElementsByTagName('body')[0];
    namespace = namespace.getAttribute('data-namespace');

    var map;
    varadouroApp.home = (function() {

        var init = function() {

            $('#filters-area-title').on('click', function() {
                $('#map-filters-container').toggleClass('display-filters');
            });

            var checkables = $('.prettyCheckable');

            for (var i = checkables.length - 1; i >= 0; i--) {
                $(checkables[i]).prettyCheckable();
            }

            if ($('#map-filter-form').length > 0) {
                var mapFilterScroll = new IScroll('#map-filter-form', {
                    interactiveScrollbars: true,
                    disableTouch: true,
                    disableMouse: true,
                    mouseWheel: true,
                    scrollbars: true,
                    invertWheelDirection: false
                });
            }

            google.maps.event.addDomListener(window, 'load', googleMaps);
        };

        function googleMaps() {

            var mapOptions = {
                center: new google.maps.LatLng(-7.113179, -34.888611),
                zoom: 15,
                mapTypeControl: false
            };

            map = new google.maps.Map(document.getElementById('map'), mapOptions);

            google.maps.event.addListener(map, 'idle', function() {
                var $frm = $('#map-filter-form');
                var data = $frm.serialize();
                loadPoints(data);
            });

            $('#map-filter-form input[type="checkbox"]').on('change', function() {
                var $frm = $('#map-filter-form');
                var data = $frm.serialize();

                for(var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }

                loadPoints(data);
                $('#map-filters-container').toggleClass('display-filters');
            });
        }

        function loadPoints(formData, bounds) {

            if (bounds === undefined) {
                var bounds = map.getBounds();

                var latA = bounds.getNorthEast().lat();
                var longA = bounds.getNorthEast().lng();
                var latB = bounds.getSouthWest().lat();
                var longB = bounds.getSouthWest().lng();
            }

            $.ajax('/map_home_ajax', {
                    type: 'get',
                    datatype: 'json',
                    data: formData,

                    success: function(ajaxData) {
                        if(ajaxData.status === 'ok') {
                            for(var i = 0; i < ajaxData.espacos.length; i++) {
                                if(ajaxData.espacos[i].latitude && ajaxData.espacos[i].longitude){
                                    var markerLatLng = new google.maps.LatLng(ajaxData.espacos[i].latitude, ajaxData.espacos[i].longitude);

                                    console.log(ajaxData);

                                    var marker = new google.maps.Marker({
                                        position: markerLatLng,
                                        map: map,
                                        title: ajaxData.espacos[i].nome_espaco,
                                        icon: 'http://dev.varadourocultural.org/publico/sigla?t=' + ajaxData.espacos[i].sigla
                                    });

                                    if (ajaxData.espacos[i].foto != 0) {
                                        marker.contentString = '<div id="content">' +
                                            '<header style="text-align: center;">' +
                                            '<img src="' + BASE_URL + 'publico/thumb/' + ajaxData.espacos[i].foto + '/150/150" style="margin-top: 10px;">' +
                                            '<h1 id="firstHeading" class="firstHeading">' + ajaxData.espacos[i].nome_espaco + '</h1>' +
                                            '</header>' +
                                            '<ul>' +
                                            '<li><b>Área de atuação primária: </b>' + ajaxData.espacos[i].area_primaria + '</li>' +
                                            '<li><b>Natureza jurídica: </b>' + ajaxData.espacos[i].natureza_juridica + '</li>' +
                                            '<li><b>Endereço: </b>' + ajaxData.espacos[i].endereco + ', ' + ajaxData.espacos[i].complemento + '</li>' +
                                            '<li><b>Telefone comercial: </b>' + ajaxData.espacos[i].telefone_comercial + '</li>' +
                                            '<li><b>Horário de funcionamento: </b>' + ajaxData.espacos[i].horario + '</li>' +
                                            '<li><b>Fecha para almoço: </b>' + ajaxData.espacos[i].fechado_almoco + '</li>' +
                                            '<li><b>Site: </b><a href="' + ajaxData.espacos[i].site + '">' + ajaxData.espacos[i].site + '</a></li>' +
                                            '</ul>' +
                                            '<a href="' + BASE_URL + 'espacos/' + ajaxData.espacos[i].slug + '">' + 'Mais Informações' + '</a>' +
                                            '</div>';
                                    }
                                    else {
                                        marker.contentString = '<div id="content">' +
                                            '<header style="text-align: center;">' +
                                            '<h1 id="firstHeading" class="firstHeading">' + ajaxData.espacos[i].nome_espaco + '</h1>' +
                                            '</header>' +
                                            '<ul>' +
                                            '<li><b>Área de atuação primária: </b>' + ajaxData.espacos[i].area_primaria + '</li>' +
                                            '<li><b>Natureza jurídica: </b>' + ajaxData.espacos[i].natureza_juridica + '</li>' +
                                            '<li><b>Endereço: </b>' + ajaxData.espacos[i].endereco + ', ' + ajaxData.espacos[i].complemento + '</li>' +
                                            '<li><b>Telefone comercial: </b>' + ajaxData.espacos[i].telefone_comercial + '</li>' +
                                            '<li><b>Horário de funcionamento: </b>' + ajaxData.espacos[i].horario + '</li>' +
                                            '<li><b>Fecha para almoço: </b>' + ajaxData.espacos[i].fechado_almoco + '</li>' +
                                            '<li><b>Site: </b><a href="' + ajaxData.espacos[i].site + '">' + ajaxData.espacos[i].site + '</a></li>' +
                                            '</ul>' +
                                            '<a href="' + BASE_URL + 'espacos/' + ajaxData.espacos[i].slug + '">' + 'Mais Informações' + '</a>' +
                                            '</div>';
                                    };

                                    markers.push(marker);
                                }
                            }

                            for(var i = 0; i < ajaxData.agentes.length; i++) {
                                if(ajaxData.agentes[i].latitude && ajaxData.agentes[i].longitude){
                                    var markerLatLng = new google.maps.LatLng(ajaxData.agentes[i].latitude, ajaxData.agentes[i].longitude);

                                    var marker = new google.maps.Marker({
                                        position: markerLatLng,
                                        map: map,
                                        title: ajaxData.agentes[i].nome_responsavel,
                                        icon: 'http://dev.varadourocultural.org/publico/sigla?t=' + ajaxData.agentes[i].sigla
                                    });

                                    if (ajaxData.agentes[i].foto != 0) {
                                        marker.contentString = '<div id="content">' +
                                            '<header style="text-align: center;">' +
                                            '<img src="' + BASE_URL + 'publico/thumb/' + ajaxData.agentes[i].foto + '/150/150" style="margin-top: 10px;">' +
                                            '<h1 id="firstHeading" class="firstHeading">' + ajaxData.agentes[i].nome_responsavel + '</h1>' +
                                            '</header>' +
                                            '<ul>' +
                                            '<li><b>Área de atuação primária: </b>' + ajaxData.agentes[i].area_primaria + '</li>' +
                                            '<li><b>Natureza jurídica: </b>' + ajaxData.agentes[i].natureza_juridica + '</li>' +
                                            '<li><b>Endereço: </b>' + ajaxData.agentes[i].endereco + ', ' + ajaxData.agentes[i].complemento + '</li>' +
                                            '<li><b>Telefone comercial: </b>' + ajaxData.agentes[i].telefone_comercial + '</li>' +
                                            '<li><b>Horário de funcionamento: </b>' + ajaxData.agentes[i].horario + '</li>' +
                                            '<li><b>Fecha para almoço: </b>' + ajaxData.agentes[i].fechado_almoco + '</li>' +
                                            '<li><b>Site: </b><a href="' + ajaxData.agentes[i].site + '">' + ajaxData.agentes[i].site + '</a></li>' +
                                            '</ul>' +
                                            '<a href="' + BASE_URL + 'agentes/' + ajaxData.agentes[i].slug + '">' + 'Mais Informações' + '</a>' +
                                            '</div>';
                                    }
                                    else {
                                        marker.contentString = '<div id="content">' +
                                            '<header style="text-align: center;">' +
                                            '<h1 id="firstHeading" class="firstHeading">' + ajaxData.agentes[i].nome_responsavel + '</h1>' +
                                            '</header>' +
                                            '<ul>' +
                                            '<li><b>Área de atuação primária: </b>' + ajaxData.agentes[i].area_primaria + '</li>' +
                                            '<li><b>Natureza jurídica: </b>' + ajaxData.agentes[i].natureza_juridica + '</li>' +
                                            '<li><b>Endereço: </b>' + ajaxData.agentes[i].endereco + ', ' + ajaxData.agentes[i].complemento + '</li>' +
                                            '<li><b>Telefone comercial: </b>' + ajaxData.agentes[i].telefone_comercial + '</li>' +
                                            '<li><b>Horário de funcionamento: </b>' + ajaxData.agentes[i].horario + '</li>' +
                                            '<li><b>Fecha para almoço: </b>' + ajaxData.agentes[i].fechado_almoco + '</li>' +
                                            '<li><b>Site: </b><a href="' + ajaxData.agentes[i].site + '">' + ajaxData.agentes[i].site + '</a></li>' +
                                            '</ul>' +
                                            '<a href="' + BASE_URL + 'agentes/' + ajaxData.agentes[i].slug + '">' + 'Mais Informações' + '</a>' +
                                            '</div>';
                                    };

                                    markers.push(marker);
                                }
                            }

                            for(var i = 0; i < markers.length; i++) {
                                var marker =  markers[i];

                                google.maps.event.addListener(marker, 'click', function() {
                                    if (infowindow) {
                                        infowindow.close();
                                    }

                                    infowindow = new google.maps.InfoWindow();

                                    infowindow.setContent(this.contentString);
                                    infowindow.open(map,this);
                                });

                            }
                        }
                    },
                });
        }

        return {
            init: init
        };
    })();

    varadouroApp.location = (function () {

        var init = function () {

            window.setTimeout(function(){
                var spaceDescription = $('#space-description'),
                    spaceLocation = $('#space-location'),
                    spaceMap = $('#map'),
                    spaceDescriptionHeight = spaceDescription.height(),
                    spaceLocationHeight = spaceLocation.height(),
                    spaceMapHeight = 0;

                if (spaceDescriptionHeight > spaceLocationHeight) {
                    spaceLocation.height(spaceDescriptionHeight);
                    spaceMapHeight = spaceDescriptionHeight;
                } else {
                    spaceDescription.height(spaceLocationHeight);
                    spaceMapHeight = spaceLocationHeight;
                }

                spaceMapHeight -= spaceLocation.find('.side-heading').outerHeight() + 15;
                spaceMapHeight -= spaceLocation.find('.single-pagination').outerHeight();
                spaceMapHeight -= spaceLocation.find('.location-meta').outerHeight();

                spaceMap.height(spaceMapHeight);
            }, 2000);

            google.maps.event.addDomListener(window, 'load', googleMaps);

        };

        function googleMaps() {

            var latitude = $('.spaces-map').data('lat');
            var longitude = $('.spaces-map').data('long');;

            var markerLatLng = new google.maps.LatLng(latitude, longitude);

            var mapOptions = {
                center: markerLatLng,
                zoom: 15,
                mapTypeControl: false
            };

            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

            var marker = new google.maps.Marker({
                position: markerLatLng,
                map: map,
                icon: '../img/sprite/standard/map-location-marker.png'
            });

        }

        return {
            init: init
        };
    })();

    varadouroApp.event = (function () {

        var init = function () {

            window.setTimeout(function(){
                var spaceDescription = $('#space-description'),
                    spaceLocation = $('#space-location'),
                    spaceMap = $('#map'),
                    spaceDescriptionHeight = spaceDescription.height(),
                    spaceLocationHeight = spaceLocation.height(),
                    spaceMapHeight = 0;


                if (spaceDescriptionHeight > spaceLocationHeight) {
                    spaceLocation.height(spaceDescriptionHeight);
                    spaceMapHeight = spaceDescriptionHeight;
                } else {
                    spaceDescription.height(spaceLocationHeight);
                    spaceMapHeight = spaceLocationHeight;
                }

                spaceMapHeight -= spaceLocation.find('.side-heading').outerHeight() + 15;
                spaceMapHeight -= spaceLocation.find('.single-pagination').outerHeight();
                spaceMapHeight -= spaceLocation.find('.location-meta').outerHeight();
                spaceMapHeight -= spaceLocation.find('.event-datetime-price').outerHeight();

                spaceMap.height(spaceMapHeight);
            }, 2000);

            google.maps.event.addDomListener(window, 'load', googleMaps);

        };

        function googleMaps() {

            var latitude = $('.events-map').data('lat');
            var longitude = $('.events-map').data('long');;

            var markerLatLng = new google.maps.LatLng(latitude, longitude);

            var mapOptions = {
                center: markerLatLng,
                zoom: 15,
                mapTypeControl: false
            };

            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

            var marker = new google.maps.Marker({
                position: markerLatLng,
                map: map,
                icon: '../img/sprite/standard/map-location-marker.png'
            });
        }

        return {
            init: init
        };
    })();

    if (namespace !== '') {
        varadouroApp[namespace].init();
    }

    var container = document.getElementById('datepicker');

    if (container !== null) {
        var picker = new Pikaday({
                firstDay: 1,
                yearRange: [2014, yearRange],
                events: [
                    'Sat Jun 28 2014',
                    'Sun Jun 29 2014',
                    'Tue Jul 01 2014',
                ],
                i18n: {
                    previousMonth : 'Mês anterior',
                    nextMonth     : 'Próximo mês',
                    months        : [
                        'Janeiro',
                        'Fevereiro',
                        'Março',
                        'Abril',
                        'Maio',
                        'Junho',
                        'Julho',
                        'Agosto',
                        'Setembro',
                        'Outubro',
                        'Novembro',
                        'Dezembro'
                    ],
                    weekdays      : [
                        'Domingo',
                        'Segunda',
                        'Terça',
                        'Quarta',
                        'Quinta',
                        'Sexta',
                        'Sábado'
                    ],
                    weekdaysShort : [
                        'D',
                        'S',
                        'T',
                        'Q',
                        'Q',
                        'S',
                        'S'
                    ]
                },
                onSelect: function(date) {
                    var date = new Date(picker.toString());
                    window.location = '/agenda/' + date.getFullYear() + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getDate();
                }
            });
        container.appendChild(picker.el);
    }

    if ($('.swiper-container').length > 0) {
        var mySwiper = $('.swiper-container').swiper({
            mode:'horizontal',
            loop: true
        });
    }

    $('#search-filters input[type="checkbox"]').on('change', function() {
        var $frm = $('#search-filters');
        $frm.find('input[name="start"]').val(0);

        $.ajax($frm.attr('action'), {
            'type' : 'get',
            'datatype': 'json',
            'data': $frm.serialize(),

            'success': function(data) {
                if(data.status === 'ok') {
                    $frm.find('input[name="start"]').val(data.start);
                    $('.search-results').html(data.partial);
                    $('.load-more').show();

                    if(data.has_more == false) {
                        $('.load-more').hide();
                    }
                }
            }
        });
    });

    $('.load-more').on('click', function(event) {
        event.preventDefault();
        var $frm = $('#search-filters'),
          controller = this.getAttribute('data-controller'),
          data = {};

        switch(controller) {
            case 'busca':
                var url = $frm.attr('action');
                data = $frm.serialize();
                break;
            case 'noticias':
                var url = this.getAttribute('href');
                data.start = $(this).data('start');
                break;
            case 'agenda':
                var url = this.getAttribute('href');
                data.start = $(this).data('start');
                break;
            case 'usuario-agenda':
                var url = this.getAttribute('href');
                data.start = $(this).data('start');
                break;
            case 'espacos-agentes':
                var url = this.getAttribute('href');
                data.start_espacos = $(this).data('start-espacos');
                data.start_agentes = $(this).data('start-agentes');
                data.load_espacos = Number($(this).data('load-espacos'));
                data.load_agentes = Number($(this).data('load-agentes'));
                break;
        }

        $.ajax( url, {
            type: 'get',
            datatype: 'json',
            data: data,

            success: function(ajaxData) {
                if(ajaxData.status === 'ok') {

                    switch(controller) {
                        case 'busca':
                            $frm.find('input[name="start"]').val(ajaxData.start);
                            $('.search-results').append($(ajaxData.partial));
                            break;
                        case 'noticias':
                            $('.load-more').data('start', ajaxData.start);
                            $('.noticias-result').append($(ajaxData.partial));
                            break;
                        case 'agenda':
                            $('.load-more').data('start', ajaxData.start);
                            $('.agenda-result').append($(ajaxData.partial));
                            break;
                        case 'usuario-agenda':
                            $('.load-more').data('start', ajaxData.start);
                            $('.usuario-agenda-result').append($(ajaxData.partial));
                            break;
                        case 'espacos-agentes':
                            $('.load-more').data('load-espacos', ajaxData.load_espacos);
                            $('.load-more').data('load-agentes', ajaxData.load_agentes);
                            $('.load-more').data('start-espacos', ajaxData.start_espacos);
                            $('.load-more').data('start-agentes', ajaxData.start_agentes);
                            $('.espacos-result').append($(ajaxData.partial_espacos));
                            $('.agentes-result').append($(ajaxData.partial_agentes));
                            break;
                    }

                    if(ajaxData.has_more === false) {
                        $('.load-more').hide();
                    }
                }
            }
        });
    });

})(window, document, jQuery);
