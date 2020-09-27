<h2>Events Plugin</h2>
<form action="options.php" method="post">
    <?php
    settings_fields("EventPluginSettings");
    ?>
    <table class="form-table">
          <tbody>
            <tr>
              <th>Show Past Events ?</th>
              <td>
                <input id="past_events" type="checkbox" name="past_events" value="yes" <?php checked(get_option('past_events'),'yes') ?> />   
              </td>
            </tr>
            <tr>
              <th>Number of events listed on listing page</th>
              <td>
                <input id="events_per_page" class="" type="text" name="events_per_page" value="<?php echo get_option('events_per_page') ?>"  />   
              </td>
            </tr>
          </tbody>

    </table>
    <input type="hidden" name="_wp_http_referer" value="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>" />
<?php submit_button('save')?>
</form>
