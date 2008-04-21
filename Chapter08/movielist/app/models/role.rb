class Role < ActiveRecord::Base
  belongs_to :movie
  belongs_to :person
  
  validates_presence_of :movie_id, :person_id, :name
  
  def to_s
    [person.full_name, name].join(' - ')
  end
end
