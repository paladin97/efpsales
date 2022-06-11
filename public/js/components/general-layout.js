$('.select_list').select2({
    placeholder: 'Seleccione',
    width: '100%',
    language: {
        // You can find all of the options in the language files provided in the
        // build. They all must be functions that return the string that should be
        // displayed.
        inputTooShort: function () {
            return "Debe introducir dos o más carácteres...";
            },
        inputTooLong: function(args) {
        // args.maximum is the maximum allowed length
        // args.input is the user-typed text
        return "Ha ingresado muchos carácteres...";
        },
        errorLoading: function() {
        return "Error cargando resultados";
        },
        loadingMore: function() {
        return "Cargando más resultados";
        },
        noResults: function() {
        return "No se ha encontrado ningún registro";
        },
        searching: function() {
        return "Buscando...";
        },
        maximumSelected: function(args) {
        // args.maximum is the maximum number of items the user may select
        return "Error cargando resultados";
        }
    }
});

//Los select list del filtro no se deben borrar 
$('.select_list_filter').select2({
    placeholder: '- TODOS -',
    width: '100%',
    language: {
        // You can find all of the options in the language files provided in the
        // build. They all must be functions that return the string that should be
        // displayed.
        inputTooShort: function () {
            return "Debe introducir dos o más carácteres...";
            },
        inputTooLong: function(args) {
        // args.maximum is the maximum allowed length
        // args.input is the user-typed text
        return "Ha ingresado muchos carácteres...";
        },
        errorLoading: function() {
        return "Error cargando resultados";
        },
        loadingMore: function() {
        return "Cargando más resultados";
        },
        noResults: function() {
        return "No se ha encontrado ningún registro";
        },
        searching: function() {
        return "Buscando...";
        },
        maximumSelected: function(args) {
        // args.maximum is the maximum number of items the user may select
        return "Error cargando resultados";
        }
    }
});  
// Fin Autocompletados
// Color Picker
(function(){
    Colors = {};
    Colors.names = {
        blue: "#0000ff",
        darkblue: "#00008b",
        darkcyan: "#008b8b",
        darkgreen: "#006400",
        darkmagenta: "#8b008b",
        darkviolet: "#9400d3",
        fuchsia: "#4a134a",
        green: "#008000",
        indigo: "#4b0082",
        maroon: "#844425",
        navy: "#000080",
        olive: "#808000",
        orange: "#ffa500",
        pink: "#ffc0cb",
        purple: "#800080",
        violet: "#800080",
    };
    Colors.random = function() {
        var result;
        var count = 0;
        for (var prop in this.names)
            if (Math.random() < 1/++count)
               result = prop;
        return { name: result, rgb: this.names[result]};
    };
})();