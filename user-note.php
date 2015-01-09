<?php
/*
Plugin Name: User Note
Version: 1.0.0
Description: Add text area as user meta.
Author: digitalcube inc.
Author URI: https://digitalcube.jp/
Plugin URI: https://digitalcube.jp/
Text Domain: user-note
Domain Path: /languages
*/

$user_note = new User_Note();
$user_note->register();

class User_Note {

	public function register()
	{
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'edit_user_profile', array( $this, 'edit_user_profile' ) );
		add_action( 'profile_update', array( $this, 'profile_update' ), 10, 2 );
	}

	public function profile_update( $user_id, $old_user_data )
	{
		if ( ! self::current_user_can() ) {
			return;
		}

		if ( isset( $_POST['user-note'] ) ) {
			update_user_meta( $user_id, 'user-note', $_POST['user-note'] );
		}
	}

	public function edit_user_profile( $user )
	{
		if ( ! self::current_user_can() ) {
			return;
		}

		?>
		<?php do_action( 'user_note_table_before' ); ?>
		<table class="form-table">
			<tbody>
				<?php do_action( 'user_note_before' ); ?>
				<tr>
					<th><label for="user-note">Note</label></th>
					<td>
						<textarea id="user-note" name="user-note" rows="5" cols="30"><?php
							echo esc_textarea( get_user_meta( $user->ID, 'user-note', true ) );
						?></textarea>
					</td>
				</tr>
				<?php do_action( 'user_note_after' ); ?>
			</tbody></table>
			<?php do_action( 'user_note_table_before' ); ?>
		<?php
	}

	public static function current_user_can()
	{
		return current_user_can( apply_filters( 'user_note_role', 'edit_users' ) );
	}
}
