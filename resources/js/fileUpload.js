export function fileUpload(options = {}) {
    const maxBytes = Number(options.maxBytes ?? 10 * 1024 * 1024);
    const accept = String(options.accept ?? ".pdf,.doc,.docx");
    const multiple = Boolean(options.multiple ?? true);
    const allowedExtensions = accept
        .split(",")
        .map((item) => item.trim().toLowerCase())
        .filter((item) => item.startsWith("."));

    const isAllowedExtension = (filename) => {
        if (allowedExtensions.length === 0) {
            return true;
        }

        const ext = `.${String(filename).split(".").pop()?.toLowerCase() ?? ""}`;
        return allowedExtensions.includes(ext);
    };

    return {
        files: [],
        dragging: false,
        maxBytes,
        accept,
        multiple,

        handleFiles(fileList) {
            Array.from(fileList).forEach((file) => {
                if (!this.multiple && this.files.length >= 1) {
                    return;
                }

                if (file.size > this.maxBytes) {
                    alert(`File ${file.name} terlalu besar. Maksimal 10MB`);
                    return;
                }

                if (!isAllowedExtension(file.name)) {
                    alert(`File ${file.name} format tidak didukung.`);
                    return;
                }

                const isDuplicate = this.files.some(
                    (existingFile) =>
                        existingFile.name === file.name &&
                        existingFile.size === file.size &&
                        existingFile.lastModified === file.lastModified,
                );

                if (isDuplicate) {
                    return;
                }

                this.files.push(file);
            });
            this.syncInputFiles();
        },

        handleDrop(e) {
            this.dragging = false;
            const files = e.dataTransfer.files;
            this.handleFiles(files);
        },

        removeFile(index) {
            this.files.splice(index, 1);
            this.syncInputFiles();
        },

        viewFile(file) {
            const url = URL.createObjectURL(file);
            window.open(url, "_blank");
        },

        getFileIcon(filename) {
            const ext = filename.split(".").pop().toLowerCase();
            return ext === "pdf" ? "fas fa-file-pdf" : "fas fa-file-word";
        },

        getFileIconClass(filename) {
            const ext = filename.split(".").pop().toLowerCase();
            return ext === "pdf"
                ? "bg-red-100 text-red-600"
                : "bg-blue-100 text-blue-600";
        },

        formatFileSize(bytes) {
            return (bytes / 1024 / 1024).toFixed(2) + " MB";
        },

        syncInputFiles() {
            const dataTransfer = new DataTransfer();

            this.files.forEach((file) => {
                dataTransfer.items.add(file);
            });

            if (this.$refs.fileInput) {
                this.$refs.fileInput.files = dataTransfer.files;
            }
        },
    };
}
