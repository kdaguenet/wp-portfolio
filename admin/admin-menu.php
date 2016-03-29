<?php
function myFolioAdminMenu()
{

    // Add a new top-level menu (ill-advised):
    add_menu_page('myFolio', 'Général', 'manage_options', 'mfMasterPage', 'mfMasterPage');

    // Add a submenu to the custom top-level menu:
    add_submenu_page('mfMasterPage', 'Skill', 'Skill', 'manage_options', 'sub-page', 'mFSkill');

}

add_action('admin_menu', 'myFolioAdminMenu');

// mt_toplevel_page() displays the page content for the custom Test Toplevel menu
function mfMasterPage()
{

    //must check that the user has the required capability
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    echo "<h2>" . __('MyFolio', 'menu-test') . "</h2>";
}

// mt_sublevel_page() displays the page content for the first submenu
// of the custom Test Toplevel menu
function mFSkill()
{

    //must check that the user has the required capability
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    echo "<h2>" . __('Skill', 'menu-test') . "</h2>";

    // variables for the field and option names
    $opt_name = 'mFSkill';
    $opt_name2 = 'mFSkillDescription';
    $hidden_field_name = 'mf_submit_hidden';
    $data_field_name = 'mFSkill';

    // Read in existing option value from database
    $skillTab = get_option($opt_name);
    $descTab = get_option($opt_name2);

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {

        if (isset($_POST['addSkill'])) {

            $skillTab[] = [
                $_POST['skillName'],
                $_POST['skillCurrency'],
                $_POST['skillNameColor'],
                $_POST['skillCurrencyColor']
            ];
            update_option($opt_name, $skillTab);

        } else {
            $skillTab = array();
            for ($i = 0; $i <= $_POST['fieldNumber']; $i++) {
                if (strlen($_POST['skillName-' . $i]) != 0) {
                    $skillTab[] = [
                        $_POST['skillName-' . $i],
                        $_POST['skillCurrency-' . $i],
                        $_POST['skillNameColor-' . $i],
                        $_POST['skillCurrencyColor-' . $i]
                    ];
                }
            }

            $descTab["title"] = $_POST["title"];
            $descTab["description"] = $_POST["description"];

            update_option($opt_name, $skillTab);
            update_option($opt_name2, $descTab);
        }
        // Put a "settings saved" message on the screen
        echo "<div class=\"updated\"><p><strong>" . __('settings saved.', 'menu-test') . "</strong></p></div>";
    }

    // Now display the settings editing screen

    echo '<div class="wrap">';


    // header
    echo "<h2>" . __('Add Skill List', 'menu-test') . "</h2>";
    echo '<form name="form2" method="post" action="">';
    echo '<input type="hidden" name="' . $hidden_field_name . '" value="Y">';
    echo '<p>' . __('Name', 'menu-test');
    echo '<input type="text" name="skillName" value="" size="20"> ';
    echo __('Name Color', 'menu-test');
    echo '<input type="text" name="skillNameColor" value="" size="20"> ';
    echo __('Currency', 'menu-test');
    echo '<input type="text" name="skillCurrency" value="" size="3"> ';
    echo __('Skill Color', 'menu-test');
    echo '<input type="text" name="skillCurrencyColor" value="" size="20"></p>';
    echo '<hr />';

    echo '<p class="submit">';
    echo '<input type="submit" name="addSkill" class="button-primary" value="' . esc_attr__('Save Changes') . '" />';
    echo '</p>';
    echo '</form>';

    echo "<h2>" . __('Edit Skill List', 'menu-test') . "</h2>";
    echo "<p>" . __('To delete a skill , erase the contents of Name\'s fields', 'menu-test') . "</p>";
    // settings form
    echo '<form name="form3" method="post" action="">';
    echo '<input type="hidden" name="' . $hidden_field_name . '" value="Y">';
    echo '<p>' . __('Title', 'menu-test') . '</p>';
    echo '<input type="text" name="title" value="' . $descTab['title'] . '" size="20">';
    echo '<p>' . __('Description', 'menu-test') . '</p>';
    echo '<textarea name="description" >' . $descTab['description'] . '</textarea>';
    $i = 0;
    echo '<fieldset>';
    if($skillTab){
        foreach ($skillTab as $skill) {
            echo '<p>';
            echo __('Name', 'menu-test') . ' <input type="text" name="skillName-' . $i . '" value="' . $skill[0] . '" size="20"> ';
            echo __('Name Color', 'menu-test') . ' <input type="text" name="skillNameColor-' . $i . '" value="' . $skill[2] . '" size="20"> ';
            echo __('Currency', 'menu-test') . ' <input type="text" name="skillCurrency-' . $i . '" value="' . $skill[1] . '" size="20"> ';
            echo __('Skill Color', 'menu-test') . ' <input type="text" name="skillCurrencyColor-' . $i . '" value="' . $skill[3] . '" size="20"> ';
            echo '</p>';
            $i++;
        }
    }
    echo '</fieldset>';
    $i--;
    echo '<input type="hidden" name="fieldNumber" value="' . $i . '">';
    echo '<hr />';

    echo '<p class="submit">';
    echo '<input type="submit" name="Submit" class="button-primary" value="' . esc_attr__('Save Changes') . '" />';
    echo '</p>';

    echo '</form>';

    echo '</div>';
}