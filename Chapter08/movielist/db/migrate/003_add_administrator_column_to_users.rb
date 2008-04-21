class AddAdministratorColumnToUsers < ActiveRecord::Migration
  def self.up
    add_column :users, :administrator, :boolean, :default => false
    
    User.find(:first).update_attribute(:administrator, true) if User.count > 0
  end

  def self.down
    remove_column :users, :administrator
  end
end
