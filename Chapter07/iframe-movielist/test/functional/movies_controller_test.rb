require File.dirname(__FILE__) + '/../test_helper'

class MoviesControllerTest < ActionController::TestCase
  def test_should_get_index
    stub_for_facebook
    get :index
    assert_response :success
    assert_not_nil assigns(:movies)
  end

  def test_should_get_new
    stub_for_facebook
    login_as :quentin
    get :new
    assert_response :success
  end
  
  def test_should_require_administrator
    stub_for_facebook
    # we raise an exception here to prevent any further processing
    MoviesController.any_instance.expects(:require_admin).raises(StandardError)
    assert_raises(StandardError) do
      get :new
    end
  end

  def test_should_create_movie
    stub_for_facebook
    login_as :quentin
    assert_difference('Movie.count') do
      post :create, :movie => {:title => 'Daredevil'}
    end

    assert_redirected_to movie_path(assigns(:movie))
  end
  
  def test_failed_create_should_rerender_form
    stub_for_facebook
    login_as :quentin
    post :create, :movie => {}
    assert_template 'new'
  end

  def test_should_show_movie
    stub_for_facebook
    get :show, :id => movies(:one).id
    assert_response :success
  end

  def test_should_get_edit
    stub_for_facebook
    login_as :quentin
    get :edit, :id => movies(:one).id
    assert_response :success
  end

  def test_should_redirect_after_good_update
    stub_for_facebook
    login_as :quentin
    put :update, :id => movies(:one).id, :movie => { :title => 'Elektra' }
    assert_redirected_to movie_path(assigns(:movie))
  end

  def test_failed_update_should_rerender_form
    stub_for_facebook
    login_as :quentin
    post :update, :id => movies(:one).id, :movie => {:title => nil}
    assert_template 'edit'
  end

  def test_should_destroy_movie
    stub_for_facebook
    login_as :quentin
    assert_difference('Movie.count', -1) do
      delete :destroy, :id => movies(:one).id
    end

    assert_redirected_to movies_path
  end
  
  def test_uploading_data_should_create_movie_image
    stub_for_facebook
    Movie.any_instance.expects(:create_image).returns(true)
    login_as :quentin
    post :update, :id => movies(:one).id, :movie => {:uploaded_data => uploaded_jpg("#{RAILS_ROOT}/test/fixtures/files/large-1.jpg")}
  end
end
