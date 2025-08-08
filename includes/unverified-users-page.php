<div class="wrap">
    <h1>Непотвърдени регистрации</h1>

    <?php
    $args = [
        'role' => 'applicant',
        'meta_query' => [
            [
                'key' => 'nvna_verified',
                'value' => '1',
                'compare' => '!=',
            ],
        ]
    ];

    $user_query = new WP_User_Query($args);
    $users = $user_query->get_results();

    if (empty($users)) {
        echo '<p>Няма непотвърдени потребители.</p>';
    } else {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>Име</th><th>Имейл</th><th>Телефон</th><th>Дата на регистрация</th></tr></thead><tbody>';

        foreach ($users as $user) {
            $name = $user->display_name;
            $email = $user->user_email;
            $phone = get_user_meta($user->ID, 'phone', true);
            $reg = $user->user_registered;

            echo "<tr><td>$name</td><td>$email</td><td>$phone</td><td>$reg</td></tr>";
        }

        echo '</tbody></table>';
    }
    ?>
</div>