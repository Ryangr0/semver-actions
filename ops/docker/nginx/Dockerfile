# Dockerfile
FROM nginx:1.21-alpine

# Remove default configuration
RUN rm /etc/nginx/conf.d/default.conf

# Copy nginx configuration
COPY nginx.conf /etc/nginx/conf.d

# Copy SSL certificates if any
# COPY ssl/ /etc/ssl/

# Expose ports
EXPOSE 80
EXPOSE 443