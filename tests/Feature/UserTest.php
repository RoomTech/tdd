<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function PHPUnit\Framework\assertCount;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */

  public function test_count_users_exactly()
  {
    return assertCount(0, User::all());
  }

  public function test_save_succefully_new_user()
  {
    $response = $this->post('users', [
      'name' => 'Da Sié Roger',
      'email' => 'dsieroger@gmail.com',
      'password' => Hash::make('password')
    ]);

    $response->assertRedirect(route('users.index'));
    $this->assertCount(1, User::all());
  }

  public function test_columns_can_not_be_empty()
  {
    $response = $this->post('users', [
      'name' => '',
      'email' => '',
      'password' => ''
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
    $this->assertCount(0, User::all());
  }

  public function test_email_must_be_unique()
  {
    User::create([
      'name' => 'Da Sié Roger',
      'email' => 'dsieroger@gmail.com',
      'password' => Hash::make('password')
    ]);

    $response = $this->post('users', [
      'name' => 'Da Sié Roger',
      'email' => 'dsieroger@gmail.com',
      'password' => Hash::make('password')
    ]);

    $response->assertSessionHasErrors(['email']);
  }


  public function test_user_can_be_updated()
  {
    $this->post('users', [
      'name' => 'Da Sié Roger',
      'email' => 'dsieroger@gmail.com',
      'password' => Hash::make('password')
    ]);
    // update user 

    $response = $this->put('users/' .  User::find(1)->id, [
      'name' => 'Da Sié Roger',
      'email' => 'dada@gmail.com'
    ]);

    $response->assertRedirect(route('users.index'));
  }


  public function test_deleted_user_succefully()
  {
    $this->post('users', [
      'name' => 'Da Sié Roger',
      'email' => 'dsieroger@gmail.com',
      'password' => Hash::make('password')
    ]);

    $response = $this->delete('users/' .  User::find(1)->id);
    $response->assertRedirect(route('users.index'));
    
  }


}
