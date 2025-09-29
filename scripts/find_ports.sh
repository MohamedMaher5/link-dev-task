# find dynamic free port
file_path="$HOME/allocated_ports/ports_$COMMIT_ID.txt"

# Check if ports are already allocated
if [ -s "$file_path" ]; then
    echo "file: ports_$COMMIT_ID.txt found. Reusing ports."
    read APP_PORT DB_PORT < "$file_path"
    export APP_PORT
    export DB_PORT
    echo "Reused allocated ports: APP_PORT=$APP_PORT, DB_PORT=$DB_PORT"
else
    ports_found=()
    # Loop through the range of ports to find two free ones
    for port in {4002..4048}; do
    # ss -tuln shows listening ports, grep checks if the port is in use
    # The grep command checks if the port is in use
    if ! ss -tuln | grep -q ":$port "; then
        ports_found+=($port)
        if [ ${#ports_found[@]} -eq 2 ]; then
        break
        fi
    fi
    done

    if [ ${#ports_found[@]} -ne 2 ]; then
    echo "Error: Could not find two free ports!" >&2
    exit 1
    fi

    APP_PORT=${ports_found[0]}
    DB_PORT=${ports_found[1]}

    # Export ports so docker-compose can see them
    export APP_PORT
    export DB_PORT
    echo "$APP_PORT $DB_PORT" > "$file_path"
    chown $USER:$USER "$file_path"  # Change ownership to the current user
    sync  # Ensure the file is written before proceeding
    echo "Allocated new ports: APP_PORT=$APP_PORT, DB_PORT=$DB_PORT"
fi
