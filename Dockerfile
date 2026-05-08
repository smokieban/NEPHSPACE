# Use official Nginx image
FROM nginx:alpine

# Remove default Nginx website and config
RUN rm -rf /usr/share/nginx/html/* && rm /etc/nginx/conf.d/default.conf

# Copy your custom Nginx configuration
COPY default.conf /etc/nginx/conf.d/default.conf

# Copy your website files
COPY . /usr/share/nginx/html

# Expose port 80
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]