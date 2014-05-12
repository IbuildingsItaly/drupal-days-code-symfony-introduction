/**
 * Created by Alessio Barnini
 * Twitter: @barno7
 * Email : alessio@ibuildings.it
 * Linkedin : https://www.linkedin.com/in/alessiobarnini
 * Web : http://www.ibuildings.it
 */
$(document).ready(function () {
    $("#more").click(function () {
        carica_utente($(this));
    });
});

function carica_utente(btn) {
    if (!btn.is("[load]")) {

        $.ajax({
            type: "GET",
            url: Routing.generate('utenti_ajax', {'totale_utenti': $('ul#lista_utenti li').length}),
            dataType: "json",
            beforeSend: function () {
                btn.attr('load', true); //aggiungo attributo per evitare molte chiamate
                btn.html('Loading...');
            },
            success: function (msg) {
                console.dir(msg);
                if (msg.status === "OK") {
                    $($.parseHTML(msg.template)).appendTo('ul#lista_utenti').hide().fadeIn(500).promise().done(function () {
                        if (!msg.mostra_pulsante) { //Abbiamo caricato tutti gli utenti
                            btn.fadeOut('normal', function() {
                                btn.remove();
                            });
                        }
                    })
                } else {
                    console.log("KO");
                    alert("Si è verificato un errore");
                }
            },
            error: function (msg) {
            },
            complete: function (msg) {
                btn.removeAttr('load');
                btn.html('Carica altri utenti');
            }
        });
    }
}