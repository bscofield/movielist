class Person < ActiveRecord::Base
  include Imageable
  
  cattr_reader :per_page
  @@per_page = 10

  has_many :roles, :dependent => :destroy
  has_many :movies, :through => :roles
  
  
  validates_presence_of :first_name, :last_name
  
  def full_name
    [first_name, last_name].join(' ')
  end
end
