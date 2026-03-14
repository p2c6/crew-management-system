import { Crew } from "./Crew";
import { DocumentType } from "./DocumentType";

export type Document = {
    id: number;
    crew: Crew;
    document_type: DocumentType;
    file_name: string;
    file_path: string;
    code: string;
    issued_date: string;
    expiry_date: string;
}

type DocumentRelations = 'crew' | 'document_type' | 'user';

type DocumentFormExcluded =
  | DocumentRelations
  | 'id'
  | 'file_name'
  | 'file_path';

export type StoreDocumentForm = Omit<Document, DocumentFormExcluded> & {
    crew_id: number | string;
    document_type_id: number | string;
    documents: (File | string | any)[]
};

export type UpdateDocumentForm = Omit<Document, DocumentFormExcluded> & {
    document_type_id: number | string;
    documents: (File | string | any)[]
    existing_document_id?: number | null;
    remove_existing_document?: boolean;
};

export type DeleteDocumentForm = {
    id: number;
}