require File.dirname(__FILE__) + '/../test_helper'

class InterestsControllerTest < ActionController::TestCase
  def test_index_should_require_login
    # we raise an exception here to prevent any further processing
    InterestsController.any_instance.expects(:login_required).raises(StandardError)
    assert_raises(StandardError) do
      get :index
    end
  end

  def test_index_should_render
    login_as :quentin
    get :index
    assert_template 'index'
  end
  
  def test_index_should_render_json_string
    login_as :quentin
    get :index, :format => 'json'
    assert_equal @response.body, users(:quentin).interests.to_json(:methods => [:movie_title])
  end
  
  def test_create_should_add_interest
    login_as :quentin
    assert_difference 'Interest.count' do
      post :create, :interest => {:movie_id => movies(:one).id}
    end
  end
  
  def test_destroy_should_remove_interest
    login_as :quentin
    assert_difference 'Interest.count', -1 do
      delete :destroy, :id => interests(:one).id
    end
  end
end
