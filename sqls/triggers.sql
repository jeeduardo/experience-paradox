-- UPDATE sales_today TABLE WHEN an order is placed for today...
DELIMITER $$
DROP TRIGGER IF EXISTS trg_update_sales_today$$
CREATE TRIGGER trg_update_sales_today
AFTER INSERT ON orders FOR EACH ROW 
BEGIN
  SET @COUNT =(SELECT COUNT(*) FROM sales_today WHERE date_today = CURRENT_DATE);
  IF @COUNT = 0 THEN
    INSERT INTO
      sales_today (
        `year`,
        `month`,
        `day`,
        `date_today`,
        `order_amount_total`,
        `orders_total`,
        `created_at`,
        `updated_at`
      )
    VALUES
      (
        year(current_date),
        lpad(month(current_date), 2, '0'),
        lpad(day(current_date), 2, '0'),
        current_date,
        NEW.grand_total,
        1,
        NOW(),
        NOW()
      );
  ELSE
    UPDATE
      sales_today
    SET
      sales_today.order_amount_total = sales_today.order_amount_total + NEW.grand_total,
      sales_today.orders_total = sales_today.orders_total + 1,
      sales_today.updated_at = NOW()
    WHERE
      sales_today.year = year(current_date)
      AND sales_today.month = lpad(month(current_date), 2, '0')
      AND sales_today.day = lpad(day(current_date), 2, '0')
      AND sales_today.date_today = current_date;
  END IF;
END;
$$
DELIMITER ;


