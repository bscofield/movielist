class Audit < ActiveRecord::Base
  belongs_to :record, :polymorphic => true
end
