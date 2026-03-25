export type DocumentType = {
    id: number;
    name: string;
}

export type StoreDocumentTypeForm = {
    name: string;
};

export type UpdateDocumentTypeForm = DocumentType;

export type DeleteDocumentTypeForm = {
    id: number;
}