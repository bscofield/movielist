require File.dirname(__FILE__) + '/../test_helper'

class ReleasesControllerTest < ActionController::TestCase
  def test_index_should_set_instance_variables
    stub_for_facebook
    get :index

    assert assigns(:releases)
    assert assigns(:own_releases)
    assert assigns(:friend_releases)
  end
end
