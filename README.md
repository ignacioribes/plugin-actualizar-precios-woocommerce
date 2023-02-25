# plugin-actualizar-precios-woocommerce
Actualizar precios de todos los productos automaticamente cada mes

Al instalar el plugin se actualizan automaticamente todos los precios de los productos un 5% por mes.

Para ver las opciones del plugin, deberías ir a la página "Configuración > General" en el panel de administración de WordPress. Allí verás una nueva sección con el título "Actualizar precio de producto cada mes", que contiene el campo de entrada para el valor multiplicador que se usará para actualizar los precios de los productos cada mes.

## Preguntas frecuentes

### ¿La actualizacion se hace el primer dia del mes?
Sí, la actualización se hará cada mes en el primer día del mes. Esto se debe a que en la línea de código que programó la tarea cron, se establece la frecuencia de ejecución en 'monthly', lo que significa que la tarea se ejecutará una vez al mes, y la hora de inicio se establece en la hora actual en la que se activa el plugin mediante la función wp_schedule_event( time(), 'monthly', 'update_product_prices_cron' );.

Entonces, si activas el plugin el día 10 del mes, la primera actualización de precios se realizará en el primer día del siguiente mes y así sucesivamente. Si deseas cambiar la fecha de actualización, puedes modificar el código para que se ejecute en un día específico del mes. Por ejemplo, si deseas que la actualización se realice el día 15 de cada mes, puedes cambiar el código a wp_schedule_event( strtotime('15th day of every month'), 'monthly', 'update_product_prices_cron' );.
