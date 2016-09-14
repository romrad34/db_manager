$(function () {    
    
    $('.title_select_db').eq(0).addClass('active_part');

    // widget management
    $.widget("custom.combobox", {
        _create: function () {
            this.wrapper = $("<span>").addClass("custom-combobox").insertAfter(this.element);
            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        }
        , _createAutocomplete: function () {
            var selected = this.element.children(":selected")
                , value = selected.val() ? selected.text() : "";
            this.input = $("<input>").appendTo(this.wrapper).val(value).attr("title", "").addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left").autocomplete({
                delay: 0
                , minLength: 0
                , source: $.proxy(this, "_source")
                , select: function (event, ui) {
                    if($($(this).parent().parent()[0].firstElementChild).is("#select_db_name")) //gestion du changement du select pour laisser afficher ou non la liste de la catégorie correspondante
                    {
                        $('#select_table_name').parent().find('input').val('');
                        $('#select_fields_name').parent().find('input').val('');
                    }
                    if($($(this).parent().parent()[0].firstElementChild).is("#select_table_name"))
                    {
                        $('#select_fields_name').parent().find('input').val('');
                    }
                }
            }).tooltip({
                classes: {
                    "ui-tooltip": "ui-state-highlight"
                }
            });
            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                }
                , autocompletechange: "_removeIfInvalid"
            , });
        }
        , _createShowAllButton: function () {
            var input = this.input
                , wasOpen = false;
            $("<a>").attr("tabIndex", -1).attr("title", "Show All Items").tooltip().appendTo(this.wrapper).button({
                icons: {
                    primary: "ui-icon-triangle-1-s"
                }
                , text: false
            }).removeClass("ui-corner-all").addClass("custom-combobox-toggle ui-corner-right").on("mousedown", function () {
                wasOpen = input.autocomplete("widget").is(":visible");
            }).on("click", function () {
                input.trigger("focus");
                // Close if already visible
                if (wasOpen) {
                    return;
                }
                // Pass empty string as value to search for, displaying all results
                input.autocomplete("search", "");
            });
        }
        , _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                if (this.value && (!request.term || matcher.test(text))) return {
                    label: text
                    , value: text
                    , option: this
                };
            }));
        }
        , _removeIfInvalid: function (event, ui) {
            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }
            // Search for a match (case-insensitive)
            var value = this.input.val()
                , valueLowerCase = value.toLowerCase()
                , valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });
            // Found a match, nothing to do
            if (valid) {
                return;
            }
            // Remove invalid value
            this.input.val("").attr("title", value + " didn't match any item").tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.tooltip("close").attr("title", "");
            }, 2500);
            this.input.autocomplete("instance").term = "";
        }
        , _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });
    
    //we attach the jquery ui combobox with the 3 select elements
    $('#select_db_name').combobox();
    $('#select_table_name').combobox();
    $('#select_fields_name').combobox();
    
    //click on "valider" database button
    $($('.submit_button').get(0)).click(function (e) {
        e.preventDefault();
            $('.title_select_db').eq(0).removeClass('active_part');
            $('.title_select_db').eq(1).addClass('active_part');
            $('.title_select_db').eq(2).removeClass('active_part');
        if ($('#select_db_name').val() === '') {
            alert('veuillez renseiger la base de donnée');
            return;
        }
        else
        {
            displayTables();
        }
    });
    //click on "valider" table button
    $($('.submit_button').get(1)).click(function (e) {
    e.preventDefault();
    $('.title_select_db').eq(1).removeClass('active_part');
    $('.title_select_db').eq(2).addClass('active_part');
        
    //  refresh of the display of the tables list
    $('#table_name').text($('#select_table_name').val());

    if ($('#select_table_name').val() === '')
    {
        alert('veuillez renseiger la table');
        return;
    }
    else 
    {
        displayFields();
    }
    });
    $('#logoff_icon').click(function () {
        $.ajax({
            method: "POST"
            , url: "../controllers/Logoff.php"
        }).done(function () {
            window.location.replace('../index.php');
        });
    });
    // BDD Creation
    $('#confirm_newdb_name').on('click', function () {
        $('#tool_tip_new_db').css('display', 'none');
        $('#selection_main').css('opacity', '1');
        $.ajax({
            method: "POST"
            , url: "../controllers/CreateDb.php"
            , data: {
                database_name: $('#input_newdb_name').val()
            }
        }).done(function (msg) {
            if (msg === 'err101') {
                alert('Probleme de connexion à la base de donnée veuillez contacter votre adminstrateur');
                window.location.reload();
            }
            else
            {
                displayDb();
              
            }
        });
    });
    // BDD suppression
    $('#delete_icon_db').click(function () {
        if (confirm("Confirmez-vous la suppression de la base de donnée " + $('#select_db_name').val())) {
            $.ajax({
                method: "POST"
                , url: '../controllers/DeleteDb.php'
                , data: {
                    database_name: $('#select_db_name').val()
                }
            }).done(function (msg) {
                alert('La base de donnée a bien été supprimée');
                window.location.reload();
            });
        }
    });
   
    // BDD export
    $('.menu_button').eq(0).click(function(){
       $.ajax({
          method:'POST',
           url: '../controllers/ExportBdd.php',
           data: {
               dbname: $('#select_db_name').val()
           }
       }).done(function(msg){
           alert ('Le fichier a été crée dans le dossier ExportBdd');
           
       });
    });
    
    // table edition
    $('#confirm_rename_table').click(function () {
        if ($('#select_table_name').val() !== null) {
            if (confirm("Souhaitez-vous renommer votre table? " + $('#select_table_name').val())) {
                $.ajax({
                    method: "POST"
                    , url: '../controllers/EditTable.php'
                    , data: {
                        dbname: $('#select_db_name').val()
                        , tablename: $('#select_table_name').val(),
                        newtablename: $('#input_rename_table').val()
                    , }
                }).done(function (msg) {
                    alert('La base de donnée a bien été renommée');
                    window.location.reload();
                });
            }
        }
        else {
            alert('Aucune table selectionnée');
            return;
        }
    });
    // table suppression
    $('#delete_icon_table').on('click', function () {
        if (confirm("Confirmez-vous la suppression de la table " + $('#select_table_name').val())) {
            $.ajax({
                method: "POST"
                , url: "../controllers/DeleteTables.php"
                , data: {
                    dbname: $('#select_db_name').val()
                    , tablename: $('#select_table_name').val()
                }
            }).done(function (msg) {
                alert('La table a bien été supprimée');
                window.location.reload();
            });
        }
    });
    
        //table export
    $('.menu_button').eq(1).click(function(){
        if ($('#select_table_name').val()===null)
        {
            alert('Veuillez renseigner la table à exporter');
            return;
        }
        else
        {
            $.ajax({
                method:'POST',
                url: '../controllers/ExportTable.php',
                data: {
                dbname: $('#select_db_name').val(),
                tablename: $('#select_table_name').val()
           }
           }).done(function(msg){
                alert ('Le fichier a été crée dans le dossier ExportTable');         
           });
        }   

    });
    
    
    //table creation   
    $('.index_field').on('change', function(){
        if ($(this).val()!=='')
        {
            $(this).parent().parent().find('.isnull_field').prop('disabled', true);
        }
        else
        {
         $(this).parent().parent().find('.isnull_field').prop('disabled', false);
        }

    });
    $('#confirm_button').on('click', function () {
 
    
        if ($('#input_title_new_table_name').val() === '') 
        {
            alert('Veuillez saisir un nom pour la table');
            return;
        }
        if ($('.autoincrement_field').is(':checked')) 
        {
            var autoincrement_check = 'AUTO_INCREMENT';
        }
        else
        {
            var autoincrement_check = '';
        }
        if ($('.isnull_field').is(':checked'))
        {
            var isnull_check = 'DEFAUL NULL';
        }
        else 
        {
            var isnull_check = 'NOT NULL';
        }
    
        if ($('.type_field').val() === 'DATE')
        {
            var size_field = '';
        }
        else
        {
            if ($('.size_field').val()==='')
            {
                var size_field =  $('.size_field').val() ;
            }
            else
            {
                var size_field = '(' + $('.size_field').val() + ')';
            }
            
        }
        if ($('.index_field').val() === '')
        {
            var index_field = '';
        }
        else
        {
            var index_field = '(' + $('.name_field').val() + ')';
        }
        if ($('.name_field').length > 1)
        {
            var name_field_a=[];
            var type_field_a=[];
            var size_field_a=[];
            var index_field_a=[];
            var autoincrement_field_a=[];
            var isnull_field_a=[];
            $('.name_field').each(function (i)
            {
                name_field_a[i]= $(this).val();
                if ($(this).val()===''){alert('veuillez saisir un nom de champs');return;}
                else{name_field_a[i]= $(this).val();}
            });
            $('.type_field').each(function (i){
            type_field_a[i] = $(this).val();
            if ($(this).val() === 'DATE')
            {
                 size_field_a[i] = '';
            }
            else
            {
                 size_field_a[i] = '(' + $($(this).parent().parent().find('.size_field')).val() + ')';
            }       
            });
            $('.index_field').each(function (i){
            index_field_a[i]= $(this).val(); 
            if ($(this).val() === '')
            {
                 index_field_a[i] = '';
            }
            else
            {
                 index_field_a[i] = '(' + $('.name_field').val() + ')';
            }
            });
                       
            $('.autoincrement_field').each(function (i){
                if ($(this).is(':checked'))
                {
                    autoincrement_field_a[i] = 'AUTO_INCREMENT';
                }
                else
                {
                    autoincrement_field_a[i] = '';
                }
            });
            $('.isnull_field').each(function (i){
            if ($(this).is(':checked'))
            {
                 isnull_field_a[i] = 'DEFAULT NULL';
            }
            else
            {
                isnull_field_a[i] = 'NOT NULL';
            }        
            });
            
            var multi_array = [name_field_a,type_field_a,size_field_a,index_field_a,autoincrement_field_a,isnull_field_a];
 
            if (checkPrimary(index_field_a)>1)
            {
                alert('La table ne peut avoir qu\'une seule clé primaire');
                      return;
            }
            if (checkName(name_field_a)===false)
            {
                alert('2 champs ne peuvent pas avoir le meme nom');
            }
            else
            {
                $.ajax({
                method:"POST",
                url:"../controllers/CreateTable.php",
                data: 
                    {
                        dbname: $('#db_ref_new_table').text(),
                        table_name: $('#input_title_new_table_name').val(),
                        multi_array: multi_array
//                        name_field: name_field_a,
//                        type_field: type_field_a,
//                        size_field: size_field_a,
//                        index_field: index_field_a,
//                        autoincrement_field: autoincrement_field_a,
//                        isnull_field: isnull_field_a           
                    }
                }).done(function(msg)
                { 
                    displayTables();
                });
            }
        }
        else
        {      
            $.ajax({
                method:"POST",
                url:"../controllers/CreateTable.php",
                data: 
                {
                    dbname: $('#db_ref_new_table').text(),
                    table_name: $('#input_title_new_table_name').val(),
                    name_field: $('.name_field').val(),
                    type_field: $('.type_field').val(),
                    size_field: size_field,
                    index_field: index_field,
                    autoincrement_field: autoincrement_check,
                    isnull_field: isnull_check            
                }
            }).done(function(msg){
               displayTables();
            });
        }
    });
    //gestion d'un nouvelle ligne pour la création d'une table
    $('.img_add_line_new_table').on('click', function (e) {
    $('#id_new_table').append('<tr class="reference_line_table"><td><img class="img_add_line_new_table2" src="../img/Delete_remove_close_exit_trash_cancel_cross.png" alt="delete" title="Delete this database" height="20" width="20" /></td><td><input class="name_field" type="text" /> </td>  <td>  <select class="type_field"><option value="INT">INT</option><option value="VARCHAR">VARCHAR</option><option value="TEXT">TEXT</option>  <option value="DATE">DATE</option>   </select></td><td><input class="size_field" type="number" /> </td> <td><select  class="index_field"><option value=""></option><option value="PRIMARY KEY">PRIMARY KEY</option></select></td><td> <input class="autoincrement_field" type="checkbox" /> </td><td><input class ="isnull_field" type="checkbox" /></td>  </tr>');
        $('.img_add_line_new_table2').css('cursor', 'pointer');
        $('.type_field').on('change', function () {
        disableAutoincrement(this);
        disablePrimarykey(this);
        $('.index_field').on('change', function(){
        if ($(this).val()!=='')
        {
            $(this).parent().parent().find('.isnull_field').prop('disabled', true);
        }
        else
        {
         $(this).parent().parent().find('.isnull_field').prop('disabled', false);
        }
    });


    });
    $('.fields_new_table').on('click', '.img_add_line_new_table2', function () {

        $(this).parent().parent().remove();
    });
    });
    //gestion de la creation d'un nouveau champs
    $('#confirm_new_field').on('click', function(){
        $('#tool_tip_new_field').css('display', 'none');
        $('#selection_main').css('opacity', '1');
        var date_width=false;
        var isnull='';
        if($('#isnull_newfield').is(':checked'))
        {
            var isnull = 'DEFAULT NULL';
        }
        else
        {
            var isnull = "NOT NULL";
        }  
        if ($('#input_newfield_type').val()==='date')
        {   
            if ($('#input_newfield_width').val()!=='')
            {
                date_width=true;
                alert('Un champs date ne doit pas avoir de longueur');
                return;
            }
            else
            {
                var new_field_width2='('+$('#input_newfield_width').val()+')';
            }
        }
        else
        {
            if ($('#input_newfield_width').val()!=='')
            {
                var new_field_width2='('+$('#input_newfield_width').val()+')';
            }
            else
            {
                var new_field_width2='';
            }
            
        }
        if (date_width!==true)
        {
            $.ajax({
                method:'POST',
                url:"../controllers/CreateFields.php",
                data:
                {
                    table: $('#select_table_name').val(),
                    dbname: $('#select_db_name').val(),
                    new_field_name:$('#input_newfield_name').val(),
                    new_field_type: $('#input_newfield_type').val(),
                    new_field_width: new_field_width2,
                    isnullfield : isnull
                }
            }).done(function(msg){
                if(msg==='error103')
                {
                    alert('Le champs ne peut contenir que des lettres ou des chiffre');
                    return;
                }
                
                else
                {
                    console.log(msg);
                    displayFields();
                }
     
            });
        }
    });
    
    //suppression d'un champs
    $('#delete_icon_field').on('click', function(){
               if (confirm("Confirmez-vous la suppression du champs " + $('#select_fields_name').val() + ' de la table '+ $('#select_table_name').val()))
               {
               $.ajax({
                    method: "POST"
                    , url: "../controllers/DeleteFields.php"
                    , data: {
                                dbname: $('#select_db_name').val(),
                                tablename: $('#select_table_name').val(),
                                fieldname: $('#select_fields_name').val()
                            }
            }).done(function (msg) {
                if (msg==='error105')
                {
                    alert('Erreur, la table doit contenir au moins 2 champs pour pouvoir en supprimer un. Vous deveez supprimer la table si vous souhaiter supprimer ce champs.');
                    window.location.reload();
                }
                else
                {  
                alert('Le champs a bien été supprimé');
                window.location.reload();
                }
            });
        }
    });
    
    //Field edition
    $('#confirm_rename_field').on('click', function(){
            $.ajax({
                method: "POST"
                , url: "../controllers/EditFields.php"
                , data:
                {
                    dbname: $('#select_db_name').val()
                    , tablename: $('#select_table_name').val(),
                    fieldname: $('#select_fields_name').val(),
                    newfieldname: $('#input_rename_field').val()
                }
            }).done(function (msg) {
                alert('Le champs a bien été renommé');
                window.location.reload();
            });   
    });
    
    // tool_tip new db
    $(window).click(function () {
        $('#tool_tip_new_db').css('display', 'none');
        $('#selection_main').css('opacity', '1');
    });
    $('#create_icon_db').on('click', function (event) {
        event.stopPropagation();
        $('#tool_tip_new_db').css('display', 'block');
        $('#selection_main').css('opacity', '0.2');
    });
    $('.tool_tip_text').click(function (e) {
        e.stopPropagation();
        $('.tool_tip_text').css('display', 'flex');
    });
    
        // tool_tip new field
    $(window).click(function () {
        $('#tool_tip_new_field').css('display', 'none');
        $('#selection_main').css('opacity', '1');
    });
    $('#create_icon_field').on('click', function (event) {
        event.stopPropagation();
        $('#tool_tip_new_field').css('display', 'block');
        $('#selection_main').css('opacity', '0.2');
    });
    $('.tool_tip_text').click(function (e) {
        e.stopPropagation();
        $('.tool_tip_text').css('display', 'flex');
    });
    
    
    // tool_tip edit table
    $(window).click(function () {
        $('#tool_tip_edit_table').css('display', 'none');
        $('#selection_main').css('opacity', '1');
    });
    $('#edit_icon_table').on('click', function (event) {
        event.stopPropagation();
        $('#tool_tip_edit_table').css('display', 'block');
        $('#selection_main').css('opacity', '0.2');
        $('#bdd_name').text('\'' + $('#select_db_name').val() + '\'');
    });
    $('.tool_tip_text').click(function (e) {
        e.stopPropagation();
        $('.tool_tip_text').css('display', 'flex');
    }); 
    
    // tool_tip edit field
    $(window).click(function () {
        $('#tool_tip_edit_field').css('display', 'none');
        $('#selection_main').css('opacity', '1');
    });
    $('#edit_icon_field').on('click', function (event) {       
        $('#field_name_tool_tip').text($('#select_fields_name').val());
        event.stopPropagation();
        $('#tool_tip_edit_field').css('display', 'block');
        $('#selection_main').css('opacity', '0.2');
        $('#bdd_name').text('\'' + $('#select_db_name').val() + '\'');
    });
    $('.tool_tip_text').click(function (e) {
        e.stopPropagation();
        $('.tool_tip_text').css('display', 'flex');
    });
    
    
    //tooltip new table
    $('#create_icon_table').on('click', function (e) {
        e.stopPropagation();
        $('#tool_tip_create_table').css('display', 'flex');
        $('#main').css('display', 'none');
        $('#db_ref_new_table').text($('#select_db_name').val());
    });
    $('#cancel_button').click(function () {
        $('#main').css('display', 'flex');
        $('#tool_tip_create_table').css('display', 'none');
    });
    $('#confirm_button').click(function () {
        $('#main').css('display', 'flex');
        $('#tool_tip_create_table').css('display', 'none');
    });
    
    $('.type_field').on('change', function () {
        disableAutoincrement(this);
        disablePrimarykey(this);
    });
    
    function displayDb()
    {       
        $.ajax({
        method: "POST"
        , url: "../controllers/DisplayDb.php"
        , data: {
            dbname: $('#select_db_name').val()
        }
        }).done(function (msg){
        if (msg === 'err101')
        {
            alert('Probleme de connexion à la base de donnée, veuillez contacter votre administrateur');
            return;
        }
          $('#select_db_name').html('');
            var db_A = parseTables(msg);
            db_A.forEach(logArrayElements_db);
        });
        
    }
    
    function displayTables()
    {
        $.ajax({
            method: "POST"
            , url: "../controllers/DisplayTables.php"
            , data: {
                dbname: $('#select_db_name').val()
            }
            }).done(function (msg){
            if (msg === 'err102')
            {
                alert('La base de donnée est vide');
                return;
            }
            $('#select_table_name').html('');
            var tables_A = parseTables(msg);
            tables_A.forEach(logArrayElements_tables);
        });
    }
    function displayFields()
    {
            $.ajax({
            method: "POST"
            , url: "../controllers/DisplayFields.php"
            , data: {
                        table: $('#select_table_name').val(),
                        dbname: $('#select_db_name').val()
                    }
            }).done(function (msg) {
            if (msg === 'err105')
            {
                alert('la table est vide');
                return;
            }
            $('#select_fields_name').html('');
            var fields_A = parseFields(msg);
            fields_A.forEach(logArrayElements_fields);
            });
    }

    function checkName (array)
    {
        var check=true;   
        function forEachName(element, index, array)
        {
            var i=index+1;
            while (i<array.length)
            {
                if (array[i]===array[index])
                {
                    check=false;
                    return ;
                }   
                i++;  
            }                
        }
        array.forEach(forEachName);
        return check;
    }
    
    
   function checkPrimary (array)
   {
        var i=0;
        function forEachIndex(element, index, array)
        {   
            if (element!=='')
            {
               i++; 
            }
        }
        array.forEach(forEachIndex);
        return i;
    }

    function parseTables(tables)
    {
        var parser = tables.substring(1, tables.length - 1);
        var parser2 = parser.replace(/"/g, "");
        parser2 = parser2.split(",");
        return parser2;
    }

    function parseFields(fields)
    {
        var parser = fields.substring(1, fields.length - 1);
        var parser2 = parser.replace(/"/g, "");
        var parser3 = parser2.replace(/[{}]/g, "");
        parser3 = parser3.split(",");
        return parser3;
    }

    function logArrayElements_db(element, index, array)
    {
        $('#select_db_name').html($('#select_db_name').html() + '<option value=' + element + '>' + element + '</option>');
        if (index === 0) {
            var input = $('#select_db_name').parent().find('input');
            $(input).val(element);
        }
    }
    function logArrayElements_tables(element, index, array)
    {
        $('#select_table_name').html($('#select_table_name').html() + '<option value=' + element + '>' + element + '</option>');
        if (index === 0) {
            var input = $('#select_table_name').parent().find('input');
            $(input).val(element);
        }
    }

    function logArrayElements_fields(element, index, array)
    {
        if (index === 0)
        {
            var input = $('#select_fields_name').parent().find('input');
            $(input).val(element);
        }
        $('#select_fields_name').html($('#select_fields_name').html() + '<option value=' + element + '>' + element + '</option>');
    }

    function disableAutoincrement(elem)
    {
        if ($(elem).val() === 'DATE' || $(elem).val() === 'TEXT' || $(elem).val() === 'VARCHAR')
        {
            $(elem).parent().parent().find('.autoincrement_field').prop('disabled', true);
        }
        else
        {
            $(elem).parent().parent().find('.autoincrement_field').prop('disabled', false);
        }
    }

    function disablePrimarykey(elem)
    {
        if ($(elem).val() === 'DATE' || $(elem).val() === 'TEXT' || $(elem).val() === 'VARCHAR')
        {
            $(elem).parent().parent().find('.index_field').prop('disabled', true);
            $(elem).parent().parent().find('.index_field').val('');
        }
        else
        {
            $(elem).parent().parent().find('.index_field').prop('disabled', false);
        }
    }
    

});