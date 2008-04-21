require File.dirname(__FILE__) + '/../test_helper'

class PeopleControllerTest < ActionController::TestCase
  def test_should_get_index
    get :index
    assert_response :success
    assert_not_nil assigns(:people)
  end

  def test_should_get_new
    login_as :quentin
    get :new
    assert_response :success
  end

  def test_should_create_person
    login_as :quentin
    assert_difference('Person.count') do
      post :create, :person => { :first_name => 'John', :last_name => 'Cusack' }
    end

    assert_redirected_to person_path(assigns(:person))
  end
  
  def test_failed_create_should_rerender_form
    login_as :quentin
    post :create, :person => {}
    assert_template 'new'
  end

  def test_should_show_person
    get :show, :id => people(:one).id
    assert_response :success
  end

  def test_should_get_edit
    login_as :quentin
    get :edit, :id => people(:one).id
    assert_response :success
  end

  def test_should_update_person
    login_as :quentin
    put :update, :id => people(:one).id, :person => { }
    assert_redirected_to person_path(assigns(:person))
  end

  def test_failed_update_should_rerender_form
    login_as :quentin
    put :update, :id => people(:one).id, :person => {:first_name => nil}
    assert_template 'edit'
  end

  def test_should_destroy_person
    login_as :quentin
    assert_difference('Person.count', -1) do
      delete :destroy, :id => people(:one).id
    end

    assert_redirected_to people_path
  end
end
