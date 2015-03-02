/**
 * Created with IntelliJ IDEA.
 * User: pm
 * Date: 02.03.15
 * Time: 10:40
 * To change this template use File | Settings | File Templates.
 */


$(function(){
    var $headers = $('.mw-headline'),
        json = {},
        counter = 0;


    $.each($headers, function(){
        var $mwh = $(this),
            $h = $mwh.closest('h3');

        if($h.length){
            var $next = $h.next(),
                group = $mwh.text();

            if(!$next.is('table')){
                $next = $next.next();
            }

            if(!$next.is('table')){
                return true;
            }

            json[group] = {};

            var $color_rows = $next.find('tbody').find('tr');

            $.each($color_rows, function(){
                var $fields = $(this).find('td');

                var name = $($fields.get(0)).text(),
                    r = $($fields.get(3)).text(),
                    g = $($fields.get(4)).text(),
                    b = $($fields.get(5)).text(),
                    desc = $($fields.get(6)).text(),
                    adn = $($fields.get(7)).text();

                adn = adn.replace('[1]', ' Nazwa angielska koloru w HTML (skala 16 kolorów)');


                json[group][name] = {
                    'r': r,
                    'g': g,
                    'b': b,
                    'desc': desc,
                    'adn': adn
                }

                counter++;

            })

        }
    })

    console.log(json);
    console.log(counter);

    $.ajax({
        type: "POST",
        url: "get",
        data: JSON.stringify(json),
        contentType: "application/json; charset=utf-8",
        //dataType: "json",
        success: function(data){$('body').append(data);},
        failure: function(data) {
            $('body').append(data);
        }
    });
})