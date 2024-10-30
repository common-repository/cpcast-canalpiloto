<?php
class CanalPiloto extends WP_Widget
{
  function CanalPiloto()
  {
    $widget_ops = array('classname' => 'CanalPiloto',
                        'description' => 'Exibe uma lista com os 5 ultimos episódios linkando para o Canal Piloto em uma aba nova'
      );
    $this->WP_Widget('CanalPiloto', 'CPCAST', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => 'Episódios CP CAST' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
?>

<?php // Get RSS Feed(s)

#Conteudo do Widget
include_once( ABSPATH . WPINC . '/feed.php' );

$rss = fetch_feed( 'http://canalpiloto.com.br/category/cpcast/feed/' );

if ( ! is_wp_error( $rss ) ) : 

    
    $maxitems = $rss->get_item_quantity( 5 ); 

    
    $rss_items = $rss->get_items( 0, $maxitems );

endif;
?>
<ul>
  <?php foreach ( $rss_items as $item ) : ?>
    <li>
      <?php 
      $data = $item->get_date;
      ?>
      <a  href="<?php echo esc_url( $item->get_permalink() ); ?>"
      title='<?php echo "Clique para ouvir o episodio em uma aba nova" ?>' target="_blank"/>
      <?php echo esc_html( $item->get_title() ); ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
<?php
    echo $after_widget;
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("CanalPiloto");') );

#Should be worked like a Charm =)
?>