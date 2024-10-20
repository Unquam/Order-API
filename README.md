## Create, Update, View and Test Orders

- Create Order:
    - Develop functionality to create an order, including storing order details such as total amount and products.
    - Ensure that the order is saved in the database with the correct attributes.
- Update Order Status:
  - Implement a job (UpdateOrderStatusJob) that periodically sends requests to an external API to fetch the latest order statuses.
  - The external API returns the order status in JSON format, e.g., {"order_number": "12345", "status": "shipped"}.
  - Update the corresponding order in the database based on the response from the external API.
- Error Handling:
  - Ensure the system can handle errors when interacting with the external API. If an error occurs (e.g., order not found), log the error and set the order status to a default value (e.g., "pending").
- Testing:
  - Write tests for the following scenarios:
    - Order Creation: Validate that an order is created correctly and stored in the database with the right attributes.
    - Order Status Update: Test the job that updates the order status, ensuring that it processes the API response correctly and updates the order in the database.
    - API Error Handling: Write tests to simulate API errors and verify that the system logs the error and updates the order status accordingly.
- Log Monitoring:
  - Ensure that all error logs are captured appropriately when API calls fail, including relevant context information such as the order number.

## The code is not for use in commercial purposes.