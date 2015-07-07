var varadouroApp = varadouroApp || {};
(function (window, document, $) {

    var checkallTrigger = $("#checkall");

    var checkboxes = $(".checkbox-user, .checkbox-email");

    var removeAllContainer = $("#removeAll");

    function switchCheckboxesState(status) {
        checkboxes.prop("checked", status).change();
    }

    checkallTrigger.on("change", function () {
        switchCheckboxesState(this.checked);
    });

    checkboxes.on("change", function () {
        for (var i = checkboxes.length - 1; i >= 0; i--) {
            if (checkboxes[i].checked) {
                removeAllContainer.slideDown(250);

                break;
            }
            else {
                if (i === 0 && (checkboxes[i].checked === false)) {
                    removeAllContainer.slideUp(250);
                }
            }
        }
    });

    $("#hide-container").on("click", function () {
        removeAllContainer.slideUp(250);
    });

    $("textarea.wysiwyg, textarea.wysiwyg-basic").each(function() {
        var conf = {
            "language": "pt-br",
            "filebrowserImageUploadUrl": BASE_URL_ADMIN + "files/ckupload",
            "height": "450px"
        };

        if ($(this).hasClass("wysiwyg-basic")) {
            conf["removePlugins"] = "font,format,list,indent,indentlist,justify";

            if (! $(this).hasClass("wysiwyg-image"))
                conf["height"] = "250px";
        }

        if ($(this).hasClass("wysiwyg-image")) {
            conf["extraPlugins"] = "filebrowser,image,lineutils,widget,oembed";
        }

        $(this).ckeditor(conf);
    });

    if ($("#slugInput").length) {
        $("#nomeInput").slugIt({
            output: $("#slugInput")
        });

        $("#tituloInput").slugIt({
            output: $("#slugInput")
        });

        $("#nome_espacoInput").slugIt({
            output: $("#slugInput")
        });

        $("#nome_responsavelInput").slugIt({
            output: $("#slugInput")
        });
    }

    if ($(".date-picker").length) {
        $('.date-picker').datepicker({
            language: "pt-BR",
            format: "dd/mm/yyyy",
            maskInput: true,
            autoclose: true,
            pickTime: false
        });
    }

    if ($(".agentes-evento").length) {
        $(".agentes-evento table").on("click", ".column-actions button", function() {
            var $tr = $(this).parents("tr");

            if ($(this).hasClass("delete")) {
                $tr.remove();
            }
        });

        $(".agentes-evento .adicionar button").click(function() {
            var $option = $(".agentes-evento .adicionar input[type='hidden']");

            if (! $option.attr("value")) {
                alert("Por favor escolha um agente.");

                return;
            }

            var id = $option.attr("value");
            var nome = $option.text();

            $(".agentes-evento .adicionar input[type='hidden']").val("");
            $(".agentes-evento .adicionar input[type='text']").val("");
            var $tr = $(".agentes-evento table tbody tr").first().clone();
            $tr.find('input[name="id_agente[]"]').val(id);
            $tr.find('input[name="nome_agente[]"]').val(nome);
            $tr.find("span.nome").text(nome);
            $(".agentes-evento table tbody").append($tr);
            $tr.show();
        });
    }

    window.addFileMultiFile = function(fieldName) {
        var $modal = $("#frm-adicionar-arquivo");

        window.openUpUploadFrm(
            function(data) {
                $modal.modal("hide");

                var $temp = $('ul.thumbnails li input[name="' + fieldName + '[]"]');
                var $thumbs = $temp.parents("ul.thumbnails");
                var $li = $thumbs.find("li").first().clone();

                $li.find("img").attr("src", BASE_URL + "publico/thumb/" + data.arquivo_id + "/160/120");
                $li.find('input[name="' + fieldName + '[]"]').val(data.arquivo_id);

                $li.appendTo($thumbs);
                $li.show();
            },
            function(err) {
                var $alert = $modal.find("form .alert");

                $alert.find("span").html(err);
                $alert.show();
            });
    }

    window.selectSingleFile = function(field) {
        var $modal = $("#frm-adicionar-arquivo");

        window.openUpUploadFrm(
            function(data) {
                $modal.modal("hide");

                $(field).val(data.arquivo_id);
            },
            function(err) {
                var $alert = $modal.find("form .alert");

                $alert.find("span").html(err);
                $alert.show();
            });
    }

    window.openUpUploadFrm = function(callback, errback) {
        var $modal = $("#frm-adicionar-arquivo");

        function cbSubmitBtn() {
            $modal.find("form").submit();
        }

        $modal.find(".modal-footer .btn:submit").on("click", cbSubmitBtn);

        function cbSubmit(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr("action"),
                type: "post",
                dataType : "text",
                iframe: true,
                processData: false,
                data: $(this).serializeArray(),
                files: $(this).find('input[type="file"]'),

                success: function(data, textStatus, jqXHR) {
                    data = JSON.parse(data);

                    if (data.erros) {
                        errback(data.erros);
                    }
                    else {
                        callback(data);
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    errback("<p>Erro ao inserir arquivo, por favor tente novamente.</p>");
                }
            });
        }

        $modal.find("form").on("submit", cbSubmit);

        function cbHideBtn() {
            $modal.modal("hide");
        }

        $modal.find(".modal-footer .btn.cancelar").one("click", cbHideBtn);

        function cbHide() {
            $modal.find(".modal-footer .btn:submit").off("click", cbSubmitBtn);
            $modal.find("form").off("submit", cbSubmit);
            $modal.find(".modal-footer .btn.cancelar").off("click", cbHideBtn);
            $modal.off("hide", cbHide);
        }

        $modal.on("hide", cbHide);

        $modal.modal("show");
    }

    $(".thumbnails").on("click", "li .actions .btn.remover", function() {
        $(this).parents("li").remove();
    });

    $('#typeahead-agente').typeahead({
        source: function (query, process) {
            return $.get(BASE_URL + 'admin/agentes-culturais/agentes_ajax', { nome: query }, function (data) {
                ids = [];
                map = {};

                for (var i = 0; i < data.agentes.length; i++) {
                    ids[i] = data.agentes[i].id;
                    map[data.agentes[i].id] = data.agentes[i].nome_responsavel;
                };

                console.log(ids);
                console.log(map);

                return process(ids);
            });
        },

        matcher: function(item) {
            return true;
        },

        sorter: function(items) {
            return items;
        },

        updater: function(item) {
            var selected = map[item];
            $(".agentes-evento .adicionar input[type='hidden']").val(item);
            $(".agentes-evento .adicionar input[type='hidden']").text(selected);
            return selected;
        },

        highlighter: function(item) {
            var highlighted = map[item];
            return highlighted;
        }
    });


    $(".multi-field").each(function() {
        var $multiField = $(this);

        $multiField.find("table").on("click", ".column-actions button", function() {
            var $tr = $(this).parents("tr");

            if ($(this).hasClass("up") || $(this).hasClass("down")) {
                var $all = $multiField.find("table tbody tr").slice(1);
                var idx = $all.index($tr);

                if ($(this).hasClass("up")) {
                    if (idx > 0) {
                        $xtr = $($all[idx]);
                        $xtr.detach();
                        $xtr.insertBefore($($all[idx - 1]));
                    }
                }
                else if ($(this).hasClass("down")) {
                    if (idx < $all.length - 2) {
                        $xtr = $($all[idx]);
                        $xtr.detach();
                        $xtr.insertAfter($($all[idx + 1]));
                    }
                }
            }
            else if ($(this).hasClass("delete")) {
                $tr.remove();
            }
        });

        $multiField.find(".adicionar .btn.adicionar").click(function() {
            var $tr = $multiField.find("tbody tr").first().clone();
            var $data = $multiField.find(".adicionar *[data-type]");
            var $fields = $tr.find('input[type="hidden"]');
            var valid = true;

            for (var i = 0; i < $data.length; i++) {
                var $val = $($data.get(i));
                var required = ($val.data("required") == "yes");

                $val.parents(".control-group").removeClass("error");

                if (! $val.val() && required) {
                    $val.parents(".control-group").addClass("error");
                    valid = false;
                }
            }

            if (! valid) {
                alert("Um ou mais campos obrigatórios não foram preenchidos.");

                return false;
            }

            for (var i = 0; i < $data.length; i++) {
                var $field = $($fields.get(i + 1));

                if (! $field)
                    break;

                var $td = $field.parents("td");
                var $descricao = $td.find("span.descricao");
                var $val = $($data.get(i));

                if ($val.attr("data-type") == "boolean") {
                    $field.val($val.is(":checked") ? "1" : "0");
                    $descricao.text($val.is(":checked") ? "Sim" : "Não");
                    $val.removeAttr("checked");
                }
                else if (($val.attr("data-type") == "integer")
                        && ($val.get(0).tagName.toLowerCase() == "select")) {
                    $field.val($val.val());
                    $descricao.text($val.find("option:selected").text());
                    $val.val("");
                }
                else if ($val.attr("data-type") == "file") {
                    if (! $val.val())
                        continue;

                    var $img = $('<img class="img-polaroid">');
                    $img.attr("src", BASE_URL + "publico/thumb/" + $val.val() + "/130/100");
                    $descricao.html($img);
                    $field.val($val.val());
                    $val.val("");
                }
                else {
                    $field.val($val.val());
                    $descricao.text($val.val());
                    $val.val("");
                }
            }

            $tr.insertBefore($multiField.find("table tbody tr.adicionar"));
            $tr.show();

            return true;
        });
    });

    $(".miniatura-arquivo").on("click", ".btn.excluir", function(event) {
        var $controlGroup = $(this).parents(".control-group");

        $controlGroup.find('input[type="hidden"]').val("");
        $controlGroup.hide();
    });

    $(".atributos").on("change", "select", function(event) {
        var $main = $(this).parents(".multiple-selectors");
        var campo = $main.data("campo");
        var $select = $(this);
        var atrib = $select.val();

        function atualizar(dados) {
            var $selects = $main.find("select");
            var idx = $selects.index($select);

            for (var i = $selects.length - 1; i > idx; i--) {
                $($selects.get(i)).remove();
            }

            if (dados.length > 0) {
                var html = "<select>";

                html += '<option value="">-- Selecione --</option>';

                for (var i = 0; i < dados.length; i++) {
                    html += '<option value="' + dados[i].id + '">'
                    html += dados[i].nome;
                    html += "</option>";
                }

                html += "</select>";

                $main.append($(html));
            }

            $selects = $main.find("select");

            var nivel = 1;
            var max = $selects.length;

            $selects.each(function() {
                var sufixo = "";

                if (nivel < max) {
                    sufixo = "_" + nivel;
                }

                $(this).attr("name", campo + sufixo);
                nivel++;
            });
        }

        if (! atrib) {
            atualizar([]);

            return;
        }

        $.ajax({
            url: BASE_URL + "atributos_ajax/" + atrib,

            success: function(data, textStatus, jqXHR) {
                atualizar(data);
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Erro ao consultar atributos.");
            }
        });
    });


    function googleMaps() {
        var markersArray = [];

        var mapOptions = {
            center: new google.maps.LatLng(-7.113179, -34.888611),
            zoom: 18,
            mapTypeControl: false
        };

        if($('input[name="latitude"]').val()) {
            mapOptions.center = new google.maps.LatLng($('input[name="latitude"]').val(), $('input[name="longitude"]').val());
        }

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        google.maps.event.addListener(map, "click", function (e) {
            placeMarker(e.latLng);
            var lat = e.latLng.lat();
            var lng = e.latLng.lng();
            $('input[name="latitude"]').val(lat);
            $('input[name="longitude"]').val(lng);
        });

        function placeMarker(location) {
            deleteOverlays();

            var marker = new google.maps.Marker({
                position: location,
                map: map
            });

            markersArray.push(marker);

            map.setCenter(location);
        }

        function deleteOverlays() {
            if (markersArray) {
                for (i in markersArray) {
                    markersArray[i].setMap(null);
                }
            markersArray.length = 0;
            }
        }

    }

    google.maps.event.addDomListener(window, 'load', googleMaps);

})(window, document, jQuery);
