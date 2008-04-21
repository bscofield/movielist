require File.dirname(__FILE__) + '/../test_helper'

class MovieTest < ActiveSupport::TestCase
  def test_assigning_to_new_role_creates_new_role
    movie = movies(:one)
    role_hash = {'0' => {:name => 'director', :person_id => people(:one).id}}
    assert_difference 'Role.count' do
      movie.new_role = role_hash
    end
  end
  
  def test_assigning_to_deleted_roles_removes_roles
    movie = movies(:one)
    assert_difference 'Role.count', -movie.roles.count do
      movie.deleted_roles = movie.roles.map(&:id)
    end
  end
end
