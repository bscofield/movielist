require File.dirname(__FILE__) + '/../test_helper'

class NotificationsControllerTest < ActionController::TestCase
  def test_verify_index_should_require_login
    stub_for_facebook
    # we raise an exception here to prevent any further processing
    NotificationsController.any_instance.expects(:require_login_or_user).raises(StandardError)
    assert_raises(StandardError) do
      get :index
    end
  end
  
  def test_index_should_render
    stub_for_facebook
    login_as :quentin
    get :index
    assert_template 'index'
  end
  
  def test_index_should_render_with_specified_user
    stub_for_facebook
    get :index, :user_id => users(:quentin).id
    assert_template 'index'
  end
end
