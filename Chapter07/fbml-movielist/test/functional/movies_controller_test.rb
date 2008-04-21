require File.dirname(__FILE__) + '/../test_helper'

class MoviesControllerTest < ActionController::TestCase
  def test_index_should_render
    stub_for_facebook
    get :index
    assert_template 'index'
  end
  
  def test_show_should_render
    stub_for_facebook
    get :show, :id => 1
    assert_template 'show'
  end
end
