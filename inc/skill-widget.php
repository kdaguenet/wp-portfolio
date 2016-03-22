<?php

class myFolio_Skill_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('myFolio_Skill', 'Skill', array('description' => 'Display skill bar'));
    }

    public function widget($args, $instance)
    {

        echo $args['before_widget'];
        echo $args['before_title'];
        echo apply_filters('widget_title', $instance['title']);
        echo $args['after_title'];
        //recuperation des skill enregistrés
        $skillTab = get_option("mFSkill");
        $descTab = get_option("mFSkillDescription");
        if(!empty($skillTab)) {
            ?>
            <p> <?php echo $descTab['description']; ?> </p>
            <?php
        }
        echo $args['after_widget'];

    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        <?php
    }
}