require File.dirname(__FILE__) + '/../test_helper'
require 'users_controller'

# Re-raise errors caught by the controller.
class UsersController; def rescue_action(e) raise e end; end

class UsersControllerTest < Test::Unit::TestCase
  # Be sure to include AuthenticatedTestHelper in test/test_helper.rb instead
  # Then, you can remove it from this and the units test.
  include AuthenticatedTestHelper

  fixtures :users

  def setup
    @controller = UsersController.new
    @request    = ActionController::TestRequest.new
    @response   = ActionController::TestResponse.new
  end

  def test_should_allow_signup
    assert_difference 'User.count' do
      create_user
      assert_response :redirect
    end
  end

  def test_should_require_login_on_signup
    assert_no_difference 'User.count' do
      create_user(:login => nil)
      assert assigns(:user).errors.on(:login)
      assert_response :success
    end
  end

  def test_should_require_password_on_signup
    assert_no_difference 'User.count' do
      create_user(:password => nil)
      assert assigns(:user).errors.on(:password)
      assert_response :success
    end
  end

  def test_should_require_password_confirmation_on_signup
    assert_no_difference 'User.count' do
      create_user(:password_confirmation => nil)
      assert assigns(:user).errors.on(:password_confirmation)
      assert_response :success
    end
  end

  def test_should_require_email_on_signup
    assert_no_difference 'User.count' do
      create_user(:email => nil)
      assert assigns(:user).errors.on(:email)
      assert_response :success
    end
  end

  def test_should_get_edit_form
    login_as :quentin
    get :edit, :id => users(:quentin).id
    assert_template 'edit'
  end
  
  def test_should_redirect_after_update
    login_as :quentin
    put :update, :id => users(:quentin).id, :user => {}
    assert_redirected_to user_path(users(:quentin).id)
  end
  
  def test_failed_update_should_rerender_form
    login_as :quentin
    put :update, :id => users(:quentin).id, :user => {:login => nil}
    assert_template 'edit'
  end
  
  def test_index_should_render
    login_as :quentin
    get :index
    assert_template 'index'
  end
  
  def test_should_destroy_user
    login_as :quentin
    assert_difference 'User.count', -1 do
      delete :destroy, :id => users(:aaron).id
    end
  end

  protected
    def create_user(options = {})
      post :create, :user => { :login => 'quire', :email => 'quire@example.com',
        :password => 'quire', :password_confirmation => 'quire' }.merge(options)
    end
end
