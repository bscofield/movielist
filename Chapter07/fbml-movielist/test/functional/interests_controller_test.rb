require File.dirname(__FILE__) + '/../test_helper'

class InterestsControllerTest < ActionController::TestCase
  def test_create_should_add_interest
    stub_for_facebook
    assert_difference 'Interest.count' do
      post :create, :movie_id => 1
    end
  end
  
  def test_destroy_should_remove_interest
    stub_for_facebook
    assert_difference 'Interest.count', -1 do
      post :destroy, :id => interests(:one).id
    end
  end
end
