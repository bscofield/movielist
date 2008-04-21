class Movie < ActiveRecord::Base
  has_many :audits, :as => :record, :dependent => :destroy
  include Imageable

  cattr_reader :per_page
  @@per_page = 6

  has_many :comments, :dependent => :destroy
  has_many :roles, :dependent => :destroy
  has_many :people, :through => :roles
  
  has_many :interests, :dependent => :destroy
  has_many :users, :through => :interests
  
  has_many :releases, :dependent => :destroy
  
  validates_presence_of :title
  
  def new_role=(values)
    values.each do |i, hash|
      unless hash[:name].blank?
        roles.create(:person_id => hash[:person_id], :name => hash[:name])
      end
    end
  end
  
  def deleted_roles=(values)
    roles.find(*values).each(&:destroy)
  end
end
