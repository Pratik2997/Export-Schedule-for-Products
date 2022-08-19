<div class="es-table">
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Order Schedule Period</th>
                <th>Export Date</th>
                <th>Receive Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $args = array(
                'post_type' => 'export-schedule',
                'posts_per_page' => 5
            );
            $export_schedules = new WP_Query($args);
            if ($export_schedules->have_posts()) : while ($export_schedules->have_posts()) : $export_schedules->the_post();
                    $today = date('Y-m-d');
                    $today = date('Y-m-d', strtotime($today));
                    $firstOrderDate = date('Y-m-d', strtotime(get_field("order_period_date_first")));
                    $lastOrderDate = date('Y-m-d', strtotime(get_field("order_period_date_second")));
                    $orderPeriodMonthFirst = date('m', strtotime(get_field("order_period_date_first")));
                    $orderPeriodMonthSecond = date('m', strtotime(get_field("order_period_date_second")));
                    $receiveDateMonthFirst = date('m', strtotime(get_field("receive_date_first")));
                    $receiveDateMonthSecond = date('m', strtotime(get_field("receive_date_second")));
            ?>

                    <tr>
                        <td>
                            <?php
                            if ($orderPeriodMonthFirst == $orderPeriodMonthSecond) {
                                echo date('d', strtotime(get_field("order_period_date_first"))) . " - " . date('d', strtotime(get_field("order_period_date_second"))) . " " . date('F', strtotime(get_field("order_period_date_first"))) . " " . date('Y', strtotime(get_field("order_period_date_first")));
                            } else {
                                echo date('d', strtotime(get_field("order_period_date_first"))) . date('F', strtotime(get_field("order_period_date_first"))) . " - " . date('d', strtotime(get_field("order_period_date_second"))) . date('F', strtotime(get_field("order_period_date_second"))) . " " . date('Y', strtotime(get_field("order_period_date_first"))) . " ";
                            }
                            if (($today >= $firstOrderDate) && ($today <= $lastOrderDate)){
                                echo ' <svg class = "custom" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                              </svg>' . " " . "Current Period";
                            }else{
                                echo "";  
                            }

                            ?>
                        </td>
                        <td><?php echo get_field("export_date") ?></td>
                        <td>
                            <?php
                            if ($receiveDateMonthFirst == $receiveDateMonthSecond) {
                                echo date('d', strtotime(get_field("receive_date_first"))) . " - " . date('d', strtotime(get_field("receive_date_second"))) . " " . date('F', strtotime(get_field("receive_date_first"))) . " " . date('Y', strtotime(get_field("receive_date_first")));
                            } else {
                                echo date('d', strtotime(get_field("receive_date_first"))) . " " . date('F', strtotime(get_field("receive_date_first"))) . " - " . date('d', strtotime(get_field("receive_date_second"))) . " " . date('F', strtotime(get_field("receive_date_second"))) . " " . date('Y', strtotime(get_field("receive_date_first")));
                            }
                            ?>
                        </td>
                    </tr>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </tbody>
    </table>
</div>