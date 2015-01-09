<?php

class SampleTest extends WP_UnitTestCase
{
	/**
	 * @test
	 */
	public function profile_update()
	{
		$user_note = new User_Note();
		$user = $this->factory->user->create_and_get();

		$this->set_user( 'administrator' );

		$_POST['user-note'] = 'Hello!';
		$user_note->profile_update( $user->ID, $user );

		// should be same with $_POST['user-note']
		$this->assertSame( 'Hello!', get_user_meta( $user->ID, 'user-note', true ) );
	}

	/**
	 * @test
	 */
	public function profile_update_author()
	{
		$user_note = new User_Note();
		$user = $this->factory->user->create_and_get();

		$this->set_user( 'author' );

		$_POST['user-note'] = 'Hello!';
		$user_note->profile_update( $user->ID, $user );

		// should be empty.
		$this->assertSame( '', get_user_meta( $user->ID, 'user-note', true ) );
	}

	/**
	 * @test
	 */
	public function edit_user_profile_admin()
	{
		$user_note = new User_Note();
		$user = $this->factory->user->create_and_get();

		// Eidting $user as admin should be true.
		$this->set_user( 'administrator' );
		$this->expectOutputRegex( '/.+/' );
		$user_note->edit_user_profile( $user );
	}

	/**
	 * @test
	 */
	public function edit_user_profile_author()
	{
		$user_note = new User_Note();
		$user = $this->factory->user->create_and_get();

		// Eidting $user as author should be false.
		$this->set_user( 'author' );
		$this->expectOutputString( '' );
		$user_note->edit_user_profile( $user );
	}

	/**
	 * @test
	 */
	public function current_user_can()
	{
		/*
		 * should be true.
		 */
		$this->set_user( 'administrator' );
		$this->assertTrue( User_Note::current_user_can() );

		/*
		 * should be false below.
		 */
		$this->set_user( 'author' );
		$this->assertFalse( User_Note::current_user_can() );

		$this->set_user( 'editor' );
		$this->assertFalse( User_Note::current_user_can() );

		$this->set_user( 'contributor' );
		$this->assertFalse( User_Note::current_user_can() );

		$this->set_user( 'subscriber' );
		$this->assertFalse( User_Note::current_user_can() );
	}

	/**
	 * @test
	 */
	public function set_user_test()
	{
		$this->set_user( 'administrator' );
		$this->assertTrue( current_user_can( 'edit_users' ) );

		$this->set_user( 'editor' );
		$this->assertFalse( current_user_can( 'edit_users' ) );
	}

	public function set_user( $role )
	{
		$user = $this->factory->user->create_and_get( array(
			'role' => $role,
		) );

		wp_set_current_user( $user->ID, $user->user_login );
	}
}
