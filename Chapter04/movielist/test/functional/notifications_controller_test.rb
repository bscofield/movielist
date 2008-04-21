require File.dirname(__FILE__) + '/../test_helper'

class NotificationsControllerTest < ActionController::TestCase
  def test_verify_index_should_require_login
    # we raise an exception here to prevent any further processing
    NotificationsController.any_instance.expects(:require_login_or_user).raises(StandardError)
    assert_raises(StandardError) do
      get :index
    end
  end
  
  def test_index_should_render
    login_as :quentin
    get :index
    assert_template 'index'
  end
  
  def test_index_should_render_with_specified_user
    get :index, :user_id => users(:quentin).id
    assert_template 'index'
  end
  
  def test_no_user_and_no_one_specified_should_redirect
    get :index
    assert_redirected_to :new_session_path
  end
end
