<?php
session_start();
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        <title>MY phpmyadmin</title>
        <link rel="stylesheet" href="../frameworks/jquery-ui-1.12.0/jquery-ui.css" />
        <link rel="stylesheet" href="../css/style_main_page.css" /> </head>

    <body>
        <div id="title_main_page"> <img id="home_icon" src="../img/yioMey96T.png" alt="home" width="80" />DB Manager </div>
        <div id="tool_tip_create_table">
            <div id="title_new_table_name">
                <label for="input_title_new_table_name">Nom de la nouvelle table à créer dans <span id="db_ref_new_table"></span> </label>
                <br/>
                <input id="input_title_new_table_name" type="text" /> </div>
            <div class="fields_new_table">
                <table id="id_new_table">
                    <tr>
                        <th></th>
                        <th>Nom du champs</th>
                        <th>Type</th>
                        <th>Taille</th>
                        <th>Index</th>
                        <th>Auto Increment</th>
                        <th style="width:100px;">Null</th>
                    </tr>
                    <tr class="reference_line_table">
                        <td><img class="img_add_line_new_table" src="../img/1_1-512.png" width="30" height="30" /></td>
                        <td>
                            <input class="name_field" type="text" /> </td>
                        <td>
                            <select class="type_field">
                                <option value="INT">INT</option>
                                <option value="VARCHAR">VARCHAR</option>
                                <option value="TEXT">TEXT</option>
                                <option value="DATE">DATE</option>
                            </select>
                        </td>
                        <td>
                            <input class="size_field" type="number" /> </td>
                        <td>
                            <select class="index_field">
                                <option value=""></option>
                                <option value="PRIMARY KEY">PRIMARY KEY</option>
                                <!--                            <option value="UNIQUE">UNIQUE KEY</option>
                                <option value="INDEX">INDEX</option>
                                <option value="FULLTEXT">FULLTEXT</option>
-->
                            </select>
                        </td>
                        <td>
                            <input class="autoincrement_field" type="checkbox" /> </td>
                        <td>
                            <input class="isnull_field" type="checkbox" /> </td>
                    </tr>
                </table>
                <div id="submit_button_new_table">
                    <button id="confirm_button">Valider</button>
                    <button id="cancel_button">Annuler</button>
                </div>
            </div>
        </div>
        <div id="main">
            <div id="tool_tip_new_field">
                <div class="tool_tip_text">
                    <div>Veuillez entrer une valeur pour le champs
                        <br/> </div>
                    <input id="input_newfield_name" type="text" />
                    <div>Veuillez selectionner le type
                        <br/> </div>
                    <select id="input_newfield_type" name="newfield_type">
                        <option value="int">int</option>
                        <option value="varchar">varchar</option>
                        <option value="text">text</option>
                        <option value="date">date</option>
                    </select>
                    <div>Veuillez saisir la taille
                        <br/> </div>
                    <input id="input_newfield_width" type="number" />
                    <div>Null
                        <br/> </div>
                    <input id="isnull_newfield" type="checkbox" />
                    <button id="confirm_new_field">Valider</button>
                </div>
            </div>
            <div id="tool_tip_new_db">
                <div class="tool_tip_text">
                    <div>Veuillez entre le nom d'une nouvelle base de donnée
                        <br/> </div>
                    <input id="input_newdb_name" type="text" />
                    <button id="confirm_newdb_name">Valider</button>
                    <p>La base de donnée sera créée avec le jeu de caractères UTF8</p>
                </div>
            </div>
            <div id="tool_tip_edit_table">
                <div class="tool_tip_text">
                    <div>Veuillez saisir le nom avec lequel vous souhaitez renommer la table <span id="table_name_tool_tip"></span>
                        <br/> </div>
                    <input id="input_rename_table" type="text" />
                    <button id="confirm_rename_table">Valider</button>
                </div>
            </div>
            <div id="tool_tip_edit_field">
                <div class="tool_tip_text">
                    <div>Veuillez saisir le nom avec lequel vous souhaitez renommer le champs <span id="field_name_tool_tip"></span>
                        <br/> </div>
                    <input id="input_rename_field" type="text" />
                    <button id="confirm_rename_field">Valider</button>
                </div>
            </div>
            <section id="selection_main">
                <p id="title_selection_box">SELECTION</p>
                <div id="selection_table_box">
                    <form method="post" action="#">
                        <div class="select_part">
                            <div class="title_select_db">database</div>
                            <br/>
                            <div class="ui-widget">
                                <select id="select_db_name" type="text" style="font-size:small">
                                    <?php
                                foreach ($_SESSION['database_rows'] as $value)
                                {
                                   echo '<option value='.$value.'>'.$value.'</option>';
                                }
                                ?>
                                </select>
                            </div>
                            <br/>
                            <button class="submit_button" type="submit">Valider</button>
                            <div class="delete_edit_icons"><img id="create_icon_db" src="../img/1_1-512.png" alt="New table" title="Créer une nouvelle base de donnée" width="40" height="40" /><img id="delete_icon_db" src="../img/remove-icon-png-24.png" alt="delete" title="Supprimer la base de donnée" height="40" width="40" /></div>
                        </div>
                        <div class="separation"></div>
                        <div class="select_part">
                            <div class="title_select_db">table</div>
                            <br/>
                            <div class="ui-widget">
                                <select id="select_table_name" type="text"> </select>
                            </div>
                            <br/>
                            <button class="submit_button" type="submit">Valider</button>
                            <div class="delete_edit_icons"><img id="create_icon_table" src="../img/1_1-512.png" alt="New table" title="Créer une nouvelle table" width="40" height="40" /><img id="delete_icon_table" src="../img/remove-icon-png-24.png" alt="delete" title="Supprimer la table" height="40" width="40" /><img id="edit_icon_table" src="../img/edit-pen-write-icon--2.png" height="40" width="40" alt="edit" title="Renommer la table" /></div>
                        </div>
                        <div class="separation"></div>
                        <div class="select_part">
                            <div class="title_select_db">field</div>
                            <br/>
                            <div class="ui-widget">
                                <select id="select_fields_name" type="text"> </select>
                            </div>
                            <!--                            <datalist id="list_fields"> </datalist>-->
                            <br/>
                            <button class="submit_button" type="submit">Valider</button>
                            <div class="delete_edit_icons"><img id="create_icon_field" src="../img/1_1-512.png" alt="New field" width="40" height="40" title="Créer un nouveau champs" /><img id="delete_icon_field" src="../img/remove-icon-png-24.png" alt="delete" title="Supprimer le champs" height="40" width="40" /><img id="edit_icon_field" src="../img/edit-pen-write-icon--2.png" height="40" width="40" alt="edit" title="Renommer le champs" /></div>
                        </div>
                    </form>
                </div>
            </section>
            <section id="menu_main">
                <p id="title_menu_box">MENU</p> <span class="menu_button">Export BDD</span> <span class="menu_button">Export table</span> <img id="logoff_icon" width="70" src="../img/power_blue.png" alt="log off" title="se déconnecter" /></section>
<!--            <hr id="id_hr" />

            <section id="table_main">
                <div id="table_name"> </div>
                <div id="table_rows">
                    <table>
                        <tr>
                            <th> </th>
                            <th>field1</th>
                            <th> field2</th>
                            <th> field3</th>
                            <th> field4</th>
                            <th> field5</th>
                        </tr>
                        <tr>
                            <td>SQL RESULT</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                        </tr>
                        <tr>
                            <td>SQL RESULT</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                        </tr>
                        <tr>
                            <td>SQL RESULT</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                        </tr>
                        <tr>
                            <td>SQL RESULT</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                        </tr>
                        <tr>
                            <td>SQL RESULT</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                        </tr>
                        <tr>
                            <td>SQL RESULT</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                        </tr>
                        <tr>
                            <td>SQL RESULT</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                        </tr>
                        <tr>
                            <td>SQL RESULT</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                            <td>example</td>
                        </tr>
                    </table>
                </div>
                <div id="table_search_field"></div>
            </section>
-->
        </div>
        <script type="text/javascript" src="../frameworks/jquery/jquery-3.1.0.min.js"></script>
        <script type="text/javascript" src="../frameworks/jquery-ui-1.12.0/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../js/main_page.js"></script>
    </body>

    </html>