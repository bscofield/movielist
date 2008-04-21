require File.dirname(__FILE__) + '/../test_helper'

class NotificationsControllerTest < ActionController::TestCase
  def test_index_should_require_login
    # we raise an exception here to prevent any further processing
    NotificationsController.any_instance.expects(:login_required).raises(StandardError)
    assert_raises(StandardError) do
      get :index
    end
  end
  
  def test_index_should_render
    login_as :quentin
    get :index
    assert_template 'index'
  end
end
