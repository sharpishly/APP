<?php

namespace App\Models;

use Exception; // It's good practice to use Exception

class CartModel extends BaseModel // Assuming you have a BaseModel
{
    protected $table = 'migrate_cart'; // Your cart table name

    public function __construct()
    {
        parent::__construct();
        // You might initialize your database connection here
        // e.g., $this->db = new DatabaseConnection();
    }

    // ... (other existing methods like getCartByUserId, addProductToCart, etc.)

    /**
     * Clears all items from a user's cart.
     * This is typically called after a successful order has been placed.
     *
     * @param int $userId The ID of the user whose cart should be cleared.
     * @return bool True if the cart was successfully cleared, false otherwise.
     * @throws Exception If a database error occurs.
     */
    public function clearCart(int $userId): bool
    {
        try {
            // Using a generic database interaction for your bespoke framework
            // You'll need to adapt this to your actual database query builder or PDO setup.
            $query = "DELETE FROM {$this->table} WHERE user_id = :user_id";
            $statement = $this->db->prepare($query); // Assuming $this->db is your PDO or database connection object
            $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);

            if ($statement->execute()) {
                // Check if any rows were affected to confirm deletion
                return $statement->rowCount() > 0 || true; // Returns true even if cart was already empty
            }

            return false; // Deletion failed
        } catch (\PDOException $e) {
            // Log the error for debugging purposes
            error_log("Database error in CartModel::clearCart: " . $e->getMessage());
            throw new Exception("Could not clear cart due to a database error.");
        } catch (Exception $e) {
            error_log("Error in CartModel::clearCart: " . $e->getMessage());
            throw $e; // Re-throw other exceptions
        }
    }
}