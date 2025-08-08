<?php
add_action('init', 'nvna_handle_admission_registration');

function nvna_handle_admission_registration() {
    if (!isset($_POST['nvna_admission_submit'])) return;

    $first = sanitize_text_field($_POST['first_name']);
    $middle = sanitize_text_field($_POST['middle_name']);
    $last = sanitize_text_field($_POST['last_name']);
    $egn = sanitize_text_field($_POST['egn']);
    $phone = sanitize_text_field($_POST['phone']);
    $email = sanitize_email($_POST['email']);

    // Провери дали потребител с този имейл вече съществува
    if (email_exists($email)) {
        wp_die('Потребител с този имейл вече съществува.');
    }

    // Генерирай верификационен код
    $code = md5(uniqid($email, true));

    // Запази го в user_meta, но първо създай потребител със статус "pending"
    $user_id = wp_create_user($email, wp_generate_password(), $email);
    wp_update_user([
        'ID' => $user_id,
        'display_name' => "$first $middle $last",
        'first_name' => $first,
        'last_name' => $last,
    ]);
    update_user_meta($user_id, 'egn', $egn);
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'nvna_email_verification_code', $code);
    update_user_meta($user_id, 'nvna_verified', '0'); // 0 = not verified

    // Смени ролята на потребителя на "applicant"
    $user = new WP_User($user_id);
    $user->set_role('applicant');

    // Изпрати имейл с линк за потвърждение
    $verify_link = add_query_arg([
        'nvna_verify' => 1,
        'uid' => $user_id,
        'code' => $code,
    ], home_url('/kandidatstvane'));

    wp_mail($email, 'Потвърждение на регистрацията',
        "Моля, потвърдете регистрацията си като кликнете на следния линк:\n$verify_link");

    $redirect_url = add_query_arg('success', 1, home_url($_SERVER['REQUEST_URI']));
    wp_redirect($redirect_url);

    exit;
}


add_action('init', 'nvna_handle_email_verification');

function nvna_handle_email_verification() {
    if (!isset($_GET['nvna_verify'])) return;

    $uid = absint($_GET['uid']);
    $code = sanitize_text_field($_GET['code']);

    $saved_code = get_user_meta($uid, 'nvna_email_verification_code', true);
    if ($code === $saved_code) {
        update_user_meta($uid, 'nvna_verified', true);
        delete_user_meta($uid, 'nvna_email_verification_code');
        wp_die('Имейлът е успешно потвърден. Можете да влезете в системата.');
    } else {
        wp_die('Невалиден код за потвърждение.');
    }
}