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
                'posts_per_page' => 1
            );
            $export_schedules = new WP_Query($args);
            if ($export_schedules->have_posts()) : while ($export_schedules->have_posts()) : $export_schedules->the_post();
                    $value = get_post_meta(get_the_ID(), '_my_meta_value_key', true);
                    $today = date('Y-m-d');
                    if (isset($value[0]) && isset($value[1]) && isset($value[2]) && isset($value[3]) && isset($value[4])) {
                        $firstOrderDate = $value[0];
                        $lastOrderDate = $value[1];
                        $orderPeriodMonthFirst = date('m', strtotime($value[0]));
                        $orderPeriodMonthSecond = date('m', strtotime($value[1]));
                        $receiveDateMonthFirst = date('m', strtotime($value[3]));
                        $receiveDateMonthSecond = date('m', strtotime($value[4]));
                    }
            ?>

                    <tr>
                        <td>
                            <?php
                            if (isset($value[0]) && isset($value[1])) {
                                if ($orderPeriodMonthFirst == $orderPeriodMonthSecond) {
                                    echo date('d', strtotime($value[0])) . " - " . date('d', strtotime($value[1])) . " " . date('F', strtotime($value[0])) . " " . date('Y', strtotime($value[0]));
                                } else {
                                    echo date('d', strtotime($value[0])) . " ". date('F', strtotime($value[0])) . " - " . date('d', strtotime($value[1])). " " . date('F', strtotime($value[1])) . " " . date('Y', strtotime($value[0])) . " ";
                                }
                            }

                            if (($today >= $firstOrderDate) && ($today <= $lastOrderDate)) {
                                echo ' <svg class = "custom" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                              </svg>' . " " . "Current Period";
                            } else {
                                echo "";
                            }

                            ?>
                        </td>
                        <td><?php if (isset($value[2])) : echo $value[2];
                            endif; ?></td>
                        <td>
                            <?php
                            if (isset($value[3]) && isset($value[4])) {
                                if ($receiveDateMonthFirst == $receiveDateMonthSecond) {
                                    echo date('d', strtotime($value[3])) . " - " . date('d', strtotime($value[4])) . " " . date('F', strtotime($value[3])) . " " . date('Y', strtotime($value[3]));
                                } else {
                                    echo date('d', strtotime($value[3])) . " " . date('F', strtotime($value[3])) . " - " . date('d', strtotime($value[4])) . " " . date('F', strtotime($value[4])) . " " . date('Y', strtotime($value[3]));
                                }
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